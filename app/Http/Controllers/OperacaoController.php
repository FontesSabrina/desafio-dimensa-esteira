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

    /**
     * Centralizamos a lógica de filtros para usar tanto na listagem quanto na exportação
     */
    private function aplicarFiltros(Request $request)
    {
        $query = Operacao::query();

        // Filtros Exigidos pelo Edital
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('codigo')) {
            $query->where('codigo_operacao', 'like', '%' . $request->codigo . '%');
        }

        if ($request->filled('produto')) {
            // Mudança para 'like' permite buscar por parte do nome do produto (Ex: "Consig" acha "Consignado")
            $query->where('produto', 'like', '%' . $request->produto . '%');
        }

        if ($request->filled('conveniada')) {
            $query->where('conveniada_nome', 'like', '%' . $request->conveniada . '%');
        }

        if ($request->filled('cpf')) {
            $query->where('cpf', 'like', '%' . $request->cpf . '%');
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->aplicarFiltros($request);

        // MUDANÇA: Busca nomes únicos de conveniadas para alimentar o <datalist> (Autocomplete)
        $conveniadas = Operacao::distinct()->orderBy('conveniada_nome')->pluck('conveniada_nome');

        // Eager loading de parcelas e ordenação por data de criação
        $operacoes = $query->with('parcelas')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        // MUDANÇA: Passando a variável $conveniadas para a view
        return view('operacoes.index', compact('operacoes', 'conveniadas'));
    }

    public function importar(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        try {
            $request->validate([
                'planilha' => 'required|mimes:xlsx,xls,csv,txt'
            ]);

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
        $query = $this->aplicarFiltros($request)->with('parcelas');

        $fileName = 'relatorio_financeiro_' . now()->format('d_m_Y_H_i') . '.csv';

        return response()->stream(function() use($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM para Excel ler acentos corretamente

            fputcsv($file, ['Código', 'Cliente', 'CPF', 'Valor Operação', 'Status', 'Produto', 'Conveniada', 'Valor Presente'], ';');

            $query->chunk(1000, function($operacoes) use($file) {
                foreach ($operacoes as $op) {
                    $vp = $this->service->calcularVP($op);

                    fputcsv($file, [
                        $op->codigo_operacao,
                        $op->nome,
                        $op->cpf,
                        number_format($op->valor_requerido, 2, ',', '.'),
                        $op->status,
                        $op->produto,
                        $op->conveniada_nome,
                        number_format($vp, 2, ',', '.')
                    ], ';');
                }
            });

            fclose($file);
        }, 200, [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
        ]);
    }
}
