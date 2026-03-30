<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
                Operação: <span class="text-indigo-600">#{{ $operacao->codigo_operacao }}</span>
            </h2>
            <a href="{{ route('operacoes.index') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-all">
                ← Voltar para a lista
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-danger">
                    ⚠ {{ $errors->first('erro') }}
                </div>
            @endif

            {{-- RESUMO FINANCEIRO --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="card-financeiro !mb-0">
                    <p class="filter-label">Valor Requerido</p>
                    <p class="text-xl font-black text-gray-800">R$ {{ number_format($operacao->valor_requerido, 2, ',', '.') }}</p>
                </div>
                <div class="card-financeiro !mb-0">
                    <p class="filter-label">Taxa de Juros</p>
                    <p class="text-xl font-black text-indigo-600">{{ number_format($operacao->taxa_juros, 2, ',', '.') }}% a.m.</p>
                </div>
                <div class="card-financeiro !mb-0">
                    <p class="filter-label !text-red-400">Total de Juros</p>
                    <p class="text-xl font-black text-red-500">R$ {{ number_format($operacao->total_juros, 2, ',', '.') }}</p>
                </div>
                <div class="card-financeiro !mb-0 bg-indigo-600 border-indigo-700 text-white">
                    <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">Valor Presente (VP)</p>
                    <p class="text-2xl font-black">R$ {{ number_format($valorPresente, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card-financeiro !mb-0">
                    <h3 class="card-title border-b border-gray-50 pb-4">Informações do Contrato</h3>
                    <div class="info-grid">
                        <div>
                            <p class="filter-label">Cliente</p>
                            <p class="client-name">{{ $operacao->nome }}</p>
                        </div>
                        <div>
                            <p class="filter-label">CPF</p>
                            <p class="client-cpf !text-sm">{{ $operacao->cpf }}</p>
                        </div>
                        <div>
                            <p class="filter-label">Produto / Conveniada</p>
                            <p class="text-sm font-bold text-gray-600 uppercase">{{ $operacao->produto }}</p>
                            <p class="text-[10px] text-indigo-400 font-black italic">{{ $operacao->conveniada_nome }}</p>
                        </div>
                        <div>
                            <p class="filter-label">Status Atual</p>
                            <span class="badge-status badge-status-processo">{{ $operacao->status }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-financeiro !mb-0">
                    <h3 class="card-title border-b border-gray-50 pb-4">Gerenciar Operação</h3>
                    <form action="{{ route('operacoes.atualizarStatus', $operacao->id) }}" method="POST" class="space-y-4 mt-4">
                        @csrf @method('PATCH')
                        <div class="filter-group">
                            <label class="filter-label">Alterar Status para:</label>
                            <select name="status" class="input-financeiro disabled:opacity-50" {{ $operacao->status === 'PAGO AO CLIENTE' ? 'disabled' : '' }}>
                                @foreach(['DIGITANDO', 'PRÉ-ANÁLISE', 'EM ANÁLISE', 'PARA ASSINATURA', 'ASSINATURA CONCLUÍDA', 'APROVADA', 'CANCELADA', 'PAGO AO CLIENTE'] as $st)
                                    <option value="{{ $st }}" {{ $operacao->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-filter" {{ $operacao->status === 'PAGO AO CLIENTE' ? 'disabled' : '' }}>
                            Confirmar Alteração
                        </button>
                    </form>

                    @if($operacao->status === 'PAGO AO CLIENTE')
                        <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-2xl text-center">
                            <p class="text-[10px] font-black text-red-600 uppercase tracking-widest italic">
                                ⚠ Bloqueio de Segurança: Operação Finalizada
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- DETALHAMENTO DAS PARCELAS --}}
                <div class="lg:col-span-2 card-financeiro !mb-0 !p-0">
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest">Plano de Pagamento</h3>
                        <span class="badge-status bg-white shadow-none">{{ $operacao->parcelas->count() }} Parcelas</span>
                    </div>
                    <table class="table-financeira">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Vencimento</th>
                                <th>Valor Bruto</th>
                                <th class="text-right">Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($operacao->parcelas->sortBy('numero_parcela') as $parcela)
                            <tr class="table-row-hover">
                                <td class="td-codigo">#{{ $parcela->numero_parcela }}</td>
                                <td class="font-bold text-gray-600">{{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}</td>
                                <td class="font-black text-gray-900">R$ {{ number_format($parcela->valor_parcela, 2, ',', '.') }}</td>
                                <td class="text-right">
                                    <span class="badge-status {{ $parcela->status == 'PAGO' ? 'badge-status-pago' : 'bg-orange-50 text-orange-700 border-orange-100' }}">
                                        {{ $parcela->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-financeiro !mb-0">
                    <h3 class="card-title border-b border-gray-50 pb-4">Histórico do Fluxo</h3>
                    <div class="mt-6 space-y-6">
                        @foreach($operacao->logs->sortByDesc('created_at') as $log)
                            <div class="relative pl-6 border-l-2 border-indigo-100 last:border-0 pb-6">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-2 border-indigo-500"></div>
                                <p class="text-[10px] font-black text-gray-800 uppercase">{{ $log->status_novo }}</p>
                                <p class="text-[10px] text-gray-400 font-bold mt-1">{{ $log->created_at->format('d/m/Y - H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
