<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            {{-- Coluna de Ação exigida pelo processo --}}
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse($operacoes as $op)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                    {{ $op->codigo_operacao }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $op->nome }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                    R$ {{ number_format($op->valor_requerido, 2, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $op->status }}
                    </span>
                </td>
                {{-- Botão para acessar os detalhes e alterar status --}}
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <a href="{{ route('operacoes.show', $op->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150 shadow-sm">
                        Analisar
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Nenhuma operação localizada.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="p-4 border-t border-gray-50 bg-gray-50/30">
    {{ $operacoes->links() }}
</div>
