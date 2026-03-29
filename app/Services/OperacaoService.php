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
        $codigoConveniada = (int) ($row[10] ?? 1);
        $dataCriacao = isset($row[7]) ? Carbon::parse($row[7]) : now();

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
                'data_alteracao'  => now(),
            ]);

            $this->gerarParcelasMensais($operacao, $row);
            return $operacao;
        });
    }

    public function alterarStatus(Operacao $operacao, string $novoStatus)
    {
        // REGRA DO PDF: Se já foi pago, não altera mais
        if ($operacao->status === Operacao::STATUS_PAGO_AO_CLIENTE) {
            throw new \Exception("Esta operação já foi finalizada e não pode ser alterada.");
        }

        // REGRAS DE TRANSIÇÃO DO PDF
        if ($novoStatus === Operacao::STATUS_PAGO_AO_CLIENTE) {
            if ($operacao->status !== Operacao::STATUS_APROVADA) {
                throw new \Exception("A operação precisa estar APROVADA para ser paga.");
            }

            $passouPorAssinatura = $operacao->logs()
                ->where('status_novo', Operacao::STATUS_ASSINATURA_CONCLUIDA)
                ->exists();

            if (!$passouPorAssinatura) {
                throw new \Exception("A operação precisa ter a ASSINATURA CONCLUÍDA antes do pagamento.");
            }
        }

        return DB::transaction(function () use ($operacao, $novoStatus) {
            $statusAnterior = $operacao->status;

            // ALTERAÇÃO REAL: Atualizamos o campo no objeto e salvamos
            $operacao->status = $novoStatus;

            if ($novoStatus === Operacao::STATUS_PAGO_AO_CLIENTE) {
                $operacao->data_pagamento = now();
            }

            $operacao->save(); // Garante a mudança no Status Atual

            $operacao->logs()->create([
                'status_anterior' => $statusAnterior,
                'status_novo'     => $novoStatus,
                'observacao'      => "Alteração manual de status.",
                'data_alteracao'  => now(),
            ]);

            return $operacao;
        });
    }

    private function gerarParcelasMensais(Operacao $operacao, array $row)
    {
        $valorParcela = CurrencyHelper::toFloat($row[13] ?? 0);
        $dataBase = !empty($row[12]) ? Carbon::parse($row[12]) : now();
        $parcelasData = [];
        $now = now();

        for ($i = 1; $i <= $operacao->quantidade_parcelas; $i++) {
            $parcelasData[] = [
                'operacao_id'     => $operacao->id,
                'numero_parcela'  => $i,
                'data_vencimento' => $dataBase->copy()->addMonths($i - 1)->format('Y-m-d'),
                'valor_parcela'   => $valorParcela,
                'status'          => 'PENDENTE',
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }
        Parcela::insert($parcelasData);
    }

    public function calcularVP(Operacao $op)
    {
        $dataHoje = now();
        $vpTotal = 0;
        $taxaJuros = (float)($op->taxa_juros / 100);
        $taxaMulta = (float)($op->taxa_multa / 100);
        $taxaMora  = (float)($op->taxa_mora / 100);

        foreach ($op->parcelas as $p) {
            $venc = Carbon::parse($p->data_vencimento);
            $d = $dataHoje->diffInDays($venc, false);
            $V = (float)$p->valor_parcela;

            if ($d < 0) {
                $vpTotal += $V + ($V * $taxaMulta) + ($V * ($taxaMora / 30) * abs($d));
            } else {
                $vpTotal += $V / pow((1 + $taxaJuros), ($d / 30));
            }
        }
        return $vpTotal;
    }
}
