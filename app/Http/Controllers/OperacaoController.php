<?php

namespace App\Http\Controllers;

use App\Models\Operacao;
use App\Services\OperacaoService;
use App\Imports\OperacoesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OperacaoController extends Controller
{
    protected $service;

    public function __construct(OperacaoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Operacao::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->codigo, fn($q) => $q->where('codigo_operacao', 'like', '%' . $request->codigo . '%'))
            ->when($request->produto, fn($q) => $q->where('produto', 'like', '%' . $request->produto . '%'))
            ->when($request->conveniada, fn($q) => $q->where('conveniada_nome', 'like', '%' . $request->conveniada . '%'))
            ->when($request->cpf, fn($q) => $q->where('cpf', 'like', '%' . $request->cpf . '%'));

        $conveniadas = Operacao::distinct()->orderBy('conveniada_nome')->pluck('conveniada_nome');

        $operacoes = $query->with('parcelas')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('operacoes.index', compact('operacoes', 'conveniadas'));
    }

    public function importar(Request $request)
    {
        $request->validate([
            'planilha' => 'required|mimes:xlsx,xls,csv,txt'
        ]);

        try {
            Excel::import(new OperacoesImport, $request->file('planilha'));
            return back()->with('status', 'Importação concluída com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['erro' => 'Falha na importação: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $operacao = Operacao::with(['parcelas', 'logs'])->findOrFail($id);

        $valorPresente = $this->service->calcularVP($operacao);

        return view('operacoes.show', compact('operacao', 'valorPresente'));
    }

    public function atualizarStatus(Request $request, $id)
    {
        $operacao = Operacao::findOrFail($id);

        try {
            $this->service->alterarStatus($operacao, $request->status);
            return back()->with('status', 'Status atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['erro' => $e->getMessage()]);
        }
    }

    public function exportar(Request $request)
    {
        $fileName = 'relatorio_' . now()->format('d_m_Y_H_i') . '.csv';

        return response()->streamDownload(function() use($request) {
            $file = fopen('php://output', 'w');
            fputs($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Código', 'Cliente', 'CPF', 'Valor', 'Status', 'Produto', 'Conveniada', 'Valor Presente'
            ], ';');

            Operacao::query()
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->chunk(1000, function($operacoes) use($file) {
                    foreach ($operacoes as $op) {
                        fputcsv($file, [
                            $op->codigo_operacao,
                            $op->nome,
                            $op->cpf,
                            number_format($op->valor_requerido, 2, ',', '.'),
                            $op->status,
                            $op->produto,
                            $op->conveniada_nome,
                            number_format($this->service->calcularVP($op), 2, ',', '.')
                        ], ';');
                    }
                });

            fclose($file);
        }, $fileName);
    }
}
