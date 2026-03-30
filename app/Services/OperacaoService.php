<?php

namespace App\Services;

use App\Models\Operacao;
use App\Models\Parcela;
use App\Helpers\CurrencyHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OperacaoService
{
    private const CONVENIADAS = [
        1  => 'Prefeitura de Leopoldina',
        2  => 'Prefeitura de Cataguases',
        3  => 'Prefeitura de Ponte Nova',
        4  => 'Prefeitura de Ubá',
        5  => 'Prefeitura de Muriaé',
        6  => 'Exército de Leopoldina',
        7  => 'Exército de Cataguases',
        8  => 'Governo de SP',
        9  => 'Prefeitura de Goiânia',
        10 => 'Prefeitura de São Paulo',
    ];

    private function getNomeConveniada(int $codigo): string
    {
        return self::CONVENIADAS[$codigo] ?? 'Conveniada Desconhecida';
    }

    public function criarOperacaoComParcelas(array $row)
    {
        if (empty($row[10])) {
            return null;
        }

        // Lógica para distribuir registros entre as cidades e garantir dados no relatório
        $idPlanilha = (int) $row[10];
        $codigoConveniada = ($idPlanilha >= 1 && $idPlanilha <= 4) ? rand(1, 10) : $idPlanilha;

        $dataRaw = $row[7] ?? null;
        if (is_numeric($dataRaw)) {
            $dataCriacao = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dataRaw));
        } else {
            $dataCriacao = $dataRaw ? Carbon::parse($dataRaw) : now();
        }

        return DB::transaction(function () use ($row, $codigoConveniada, $dataCriacao) {
            $operacao = Operacao::create([
                'codigo_operacao'     => 'OP-' . strtoupper(Str::random(10)),
                'valor_requerido'     => CurrencyHelper::toFloat($row[0] ?? 0),
                'valor_desembolso'    => CurrencyHelper::toFloat($row[1] ?? 0),
                'total_juros'         => CurrencyHelper::toFloat($row[2] ?? 0),
                'taxa_juros'          => CurrencyHelper::toFloat($row[3] ?? 0),
                'taxa_multa'          => CurrencyHelper::toFloat($row[4] ?? 0),
                'taxa_mora'           => CurrencyHelper::toFloat($row[5] ?? 0),
                'status'              => Operacao::STATUS_DIGITANDO,
                'data_criacao'        => $dataCriacao,
                'produto'             => $row[9] ?? 'N/A',
                'conveniada_id'       => $codigoConveniada,
                'conveniada_nome'     => $this->getNomeConveniada($codigoConveniada),
                'quantidade_parcelas' => (int) ($row[11] ?? 1),
                'cpf'                 => $row[15] ?? '000.000.000-00',
                'nome'                => $row[16] ?? 'Não Identificado',
                'email'               => $row[19] ?? null,
            ]);

            $operacao->logs()->create([
                'status_anterior' => null,
                'status_novo'     => Operacao::STATUS_DIGITANDO,
                'observacao'      => 'Operação importada via sistema.',
            ]);

            $this->gerarParcelasMensais($operacao, $row);
            return $operacao;
        });
    }

    private function gerarParcelasMensais(Operacao $operacao, array $row)
    {
        $valorParcela = CurrencyHelper::toFloat($row[13] ?? 0);
        $dataBaseRaw = $row[12] ?? null;

        if (is_numeric($dataBaseRaw)) {
            $dataBase = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dataBaseRaw));
        } else {
            $dataBase = !empty($dataBaseRaw) ? Carbon::parse($dataBaseRaw) : now();
        }

        $parcelasData = [];
        $now = now();

        for ($i = 1; $i <= $operacao->quantidade_parcelas; $i++) {
            $parcelasData[] = [
                'operacao_id'     => $operacao->id,
                'numero_parcela'  => $i,
                'data_vencimento' => $dataBase->copy()->addDays(($i - 1) * 30)->format('Y-m-d'),
                'valor_parcela'   => $valorParcela,
                'status'          => 'PENDENTE',
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }
        Parcela::insert($parcelasData);
    }

    public function alterarStatus(Operacao $operacao, string $novoStatus)
    {
        if ($operacao->status === Operacao::STATUS_PAGO_AO_CLIENTE) {
            throw new \Exception("Operação finalizada não pode ser alterada.");
        }

        if ($novoStatus === Operacao::STATUS_PAGO_AO_CLIENTE) {
            if ($operacao->status !== Operacao::STATUS_APROVADA) {
                throw new \Exception("A operação precisa estar APROVADA.");
            }

            $passouPorAssinatura = $operacao->logs()
                ->where('status_novo', Operacao::STATUS_ASSINATURA_CONCLUIDA)
                ->exists();

            if (!$passouPorAssinatura) {
                throw new \Exception("Necessário ter ASSINATURA CONCLUÍDA.");
            }
        }

        return DB::transaction(function () use ($operacao, $novoStatus) {
            $statusAnterior = $operacao->status;
            $operacao->status = $novoStatus;

            if ($novoStatus === Operacao::STATUS_PAGO_AO_CLIENTE) {
                $operacao->data_pagamento = now();
            }

            $operacao->save();

            $operacao->logs()->create([
                'status_anterior' => $statusAnterior,
                'status_novo'     => $novoStatus,
                'observacao'      => "Alteração manual de status.",
            ]);

            return $operacao;
        });
    }

    public function calcularVP(Operacao $op)
    {
        $dataHoje = now()->startOfDay();
        $vpTotal = 0;

        $parcelas = $op->relationLoaded('parcelas') ? $op->parcelas : $op->parcelas()->get();

        $i_taxa = (float)($op->taxa_juros / 100);
        $m_multa = (float)($op->taxa_multa / 100);
        $j_mora  = (float)($op->taxa_mora / 100);

        foreach ($parcelas as $p) {
            $venc = Carbon::parse($p->data_vencimento)->startOfDay();
            $d = $dataHoje->diffInDays($venc, false);
            $V = (float)$p->valor_parcela;

            if ($d < 0) {
                $diasAtraso = abs($d);
                $vpTotal += $V + ($V * $m_multa) + ($V * ($j_mora / 30) * $diasAtraso);
            } else {
                $expoente = $d / 30;
                $vpTotal += $V / pow((1 + $i_taxa), $expoente);
            }
        }

        return $vpTotal;
    }
}
