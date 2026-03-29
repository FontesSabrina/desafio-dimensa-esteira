<?php

namespace App\Imports;

use App\Services\OperacaoService;
use Maatwebsite\Excel\Concerns\OnEachRow; // Mudamos para OnEachRow para melhor controle
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading; // Essencial para performance
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Log;

class OperacoesImport implements OnEachRow, WithCustomCsvSettings, WithStartRow, WithChunkReading
{
    protected $service;

    public function __construct() {
        // Injetando o service otimizado
        $this->service = new OperacaoService();
    }

    public function startRow(): int {
        return 2;
    }

    /**
     * Usamos onRow em vez de model() porque o Service já cuida
     * da criação de múltiplos registros (Operação + Parcelas + Logs).
     */
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // Validação básica de coluna
        if (!isset($data[15])) {
            Log::warning("Linha " . $row->getIndex() . " ignorada: CPF (índice 15) não encontrado.");
            return;
        }

        try {
            // Chama o service que já faz o insert das parcelas em lote (Batch)
            $this->service->criarOperacaoComParcelas($data);
        } catch (\Exception $e) {
            // Em vez de dd(), usamos Log para a importação não parar no meio se uma linha der erro
            Log::error("Erro ao importar linha " . $row->getIndex() . ": " . $e->getMessage());
        }
    }

    /**
     * chunkSize: Define quantas linhas o Laravel lê por vez.
     * 1000 é um excelente número para performance em 127.0.0.1
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array {
        return [
            'delimiter' => ';',
            'input_encoding' => 'UTF-8',
        ];
    }
}
