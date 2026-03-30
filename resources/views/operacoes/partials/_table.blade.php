<div class="overflow-x-auto border-t border-gray-100">
    <table class="table-financeira">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Produto</th>
                <th>Conveniada</th>
                <th class="text-right">Valor</th>
                <th class="text-center">Status</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($operacoes as $op)
                <tr class="table-row-hover">
                    <td class="td-codigo">
                        {{ $op->codigo_operacao }}
                    </td>
                    <td>
                        <div class="client-name font-bold text-gray-800">{{ $op->nome }}</div>
                        <div class="client-cpf text-xs text-gray-400">{{ $op->cpf }}</div>
                    </td>
                    <td class="text-xs font-semibold text-gray-600">
                        {{ $op->produto }}
                    </td>
                    <td class="text-xs text-gray-500 italic">
                        {{ $op->conveniada_nome ?? 'Não informada' }}
                    </td>
                    <td class="text-right font-mono font-black text-gray-900">
                        R$ {{ number_format($op->valor_requerido, 2, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @php
                            $statusClass = match($op->status) {
                                'PAGO AO CLIENTE' => 'badge-status-pago',
                                'CANCELADA' => 'bg-red-50 text-red-600 border-red-100',
                                'APROVADA' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                default => 'badge-status-processo'
                            };
                        @endphp
                        <span class="badge-status {{ $statusClass }}">
                            {{ $op->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('operacoes.show', $op->id) }}" class="btn-action">
                            Analisar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="table-empty-state">
                        Nenhuma operação localizada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $operacoes->appends(request()->query())->links() }}
</div>
