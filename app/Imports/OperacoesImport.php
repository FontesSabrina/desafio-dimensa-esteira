<?php

namespace App\Imports;

use App\Services\OperacaoService;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Log;

class OperacoesImport implements OnEachRow, WithCustomCsvSettings, WithStartRow, WithChunkReading
{
    protected $service;

    public function __construct() {
        $this->service = new OperacaoService();
    }

    public function startRow(): int {
        return 2;
    }

public function onRow(Row $row)
{
    $data = $row->toArray();

    try {
        $this->service->criarOperacaoComParcelas($data);
    } catch (\Exception $e) {
        dump("Erro na linha " . $row->getIndex() . ": " . $e->getMessage());
        Log::error($e->getMessage());
    }
}

    public function getCsvSettings(): array {
        return [
            'delimiter'        => ';',
            'input_encoding'   => 'UTF-8',

        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
