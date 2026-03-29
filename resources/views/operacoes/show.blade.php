<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Operação: <span class="text-blue-600">#{{ $operacao->codigo_operacao }}</span>
            </h2>
            <a href="{{ route('operacoes.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Voltar para a lista</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Resumo Financeiro - CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase">Valor Requerido</p>
                    <p class="text-lg font-bold text-gray-800">R$ {{ number_format($operacao->valor_requerido, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase">Taxa de Juros</p>
                    <p class="text-lg font-bold text-indigo-600">{{ number_format($operacao->taxa_juros, 2, ',', '.') }}% a.m.</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total de Juros</p>
                    <p class="text-lg font-bold text-red-500">R$ {{ number_format($operacao->total_juros, 2, ',', '.') }}</p>
                </div>
                <div class="bg-blue-600 p-4 rounded-xl shadow-md border border-blue-700 text-white">
                    <p class="text-xs font-bold uppercase opacity-80">Valor Presente (VP)</p>
                    <p class="text-xl font-black">R$ {{ number_format($valorPresente, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Coluna da Esquerda: Dados e Fluxo --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Dados do Cliente & Contrato</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <p><strong>Cliente:</strong> {{ $operacao->nome }}</p>
                            <p><strong>CPF:</strong> {{ $operacao->cpf }}</p>
                            <p><strong>Produto:</strong> {{ $operacao->produto }}</p>
                            <p><strong>Conveniada:</strong> {{ $operacao->conveniada_nome }}</p>
                            <p><strong>Data Criação:</strong> {{ \Carbon\Carbon::parse($operacao->data_criacao)->format('d/m/Y') }}</p>
                            <p><strong>Status Atual:</strong>
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                    {{ $operacao->status }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Tabela de Parcelas --}}
                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-700 uppercase">Plano de Pagamento ({{ $operacao->quantidade_parcelas }}x)</h3>
                        </div>
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/30">
                                <tr class="text-left text-xs font-bold text-gray-400 uppercase">
                                    <th class="px-6 py-3">Parcela</th>
                                    <th class="px-6 py-3">Vencimento</th>
                                    <th class="px-6 py-3">Valor</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($operacao->parcelas->sortBy('numero_parcela') as $parcela)
                                <tr class="text-sm">
                                    <td class="px-6 py-3 font-bold text-gray-400">{{ $parcela->numero_parcela }}º</td>
                                    <td class="px-6 py-3">{{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 font-semibold">R$ {{ number_format($parcela->valor_parcela, 2, ',', '.') }}</td>
                                    <td class="px-6 py-3">
                                        <span class="text-xs {{ $parcela->status == 'PAGO' ? 'text-green-600' : 'text-orange-600' }}">
                                            ● {{ $parcela->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Coluna da Direita: Ações e Histórico --}}
                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-inner">
                        <h3 class="text-sm font-bold text-gray-600 uppercase mb-4">Atualizar Fluxo</h3>

                        @if(session('status'))
                            <div class="mb-4 p-2 bg-green-100 text-green-700 rounded text-xs font-bold uppercase text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 p-2 bg-red-100 text-red-700 rounded text-xs font-bold">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('operacoes.atualizarStatus', $operacao->id) }}" method="POST" class="space-y-3">
                            @csrf @method('PATCH')
                            <select name="status" class="rounded-lg border-gray-300 w-full disabled:bg-gray-200" {{ $operacao->status === 'PAGO AO CLIENTE' ? 'disabled' : '' }}>
                                @foreach(['DIGITANDO', 'PRÉ-ANÁLISE', 'EM ANÁLISE', 'PARA ASSINATURA', 'ASSINATURA CONCLUÍDA', 'APROVADA', 'CANCELADA', 'PAGO AO CLIENTE'] as $st)
                                    <option value="{{ $st }}" {{ $operacao->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-md transition-all disabled:opacity-50" {{ $operacao->status === 'PAGO AO CLIENTE' ? 'disabled' : '' }}>
                                Salvar Alteração
                            </button>
                        </form>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xs font-bold text-gray-400 uppercase">Logs de Auditoria</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            @foreach($operacao->logs->sortByDesc('data_alteracao')->take(5) as $log)
                                <div class="border-l-2 border-indigo-500 pl-3 py-1">
                                    <p class="text-xs font-bold text-gray-800">{{ $log->status_novo }}</p>
                                    <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($log->data_alteracao)->format('d/m/Y H:i') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
