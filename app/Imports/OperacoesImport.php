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
        // Injetando o service que já contém a lógica de cálculo corrigida
        $this->service = new OperacaoService();
    }

    /**
     * Começamos na linha 2 para saltar o cabeçalho (Código;Cliente;CPF...)
     */
    public function startRow(): int {
        return 2;
    }


public function onRow(Row $row)
{
    $data = $row->toArray();

    // 1. Verifique se você APAGOU o dd($data) que estava aqui.
    // Se o dd() continuar aqui, o código para e nunca chega no Service!

    try {
        $this->service->criarOperacaoComParcelas($data);
    } catch (\Exception $e) {
        // Isso aqui vai cuspir o erro real no seu terminal ou no log
        // Pode ser que falte uma coluna no banco ou o CPF seja longo demais
        dump("Erro na linha " . $row->getIndex() . ": " . $e->getMessage());
        Log::error($e->getMessage());
    }
}

    /**
     * Configurações de leitura do CSV
     */
    public function getCsvSettings(): array {
        return [
            'delimiter'        => ';', // Ponto e vírgula conforme o ficheiro original
            'input_encoding'   => 'UTF-8',

        ];
    }

    /**
     * Tamanho do bloco para não estourar a memória RAM
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
