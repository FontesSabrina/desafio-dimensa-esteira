<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestão de Esteira Ativa') }}
            </h2>
            <a href="{{ route('operacoes.exportar', request()->all()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition">
                Gerar Relatório Financeiro
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SEÇÃO 1: IMPORTAÇÃO --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-4">● Importar Nova Planilha (Excel/CSV)</h3>
                <form action="{{ route('operacoes.importar') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                    @csrf
                    <input type="file" name="planilha" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold transition">
                        Iniciar Processamento
                    </button>
                </form>
            </div>

            {{-- SEÇÃO 2: FILTROS DE BUSCA (COM AUTOCOMPLETE DE CONVENIADA) --}}
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('operacoes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Código</label>
                        <input type="text" name="codigo" value="{{ request('codigo') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ex: #123">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Produto</label>
                        <input type="text" name="produto" value="{{ request('produto') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Tipo">
                    </div>

                    {{-- MUDANÇA AQUI: Input com Datalist para Autocomplete --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Conveniada</label>
                        <input type="text" name="conveniada" list="conveniadas_list" value="{{ request('conveniada') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Digite para buscar...">

                        <datalist id="conveniadas_list">
                            @foreach($conveniadas as $nome)
                                <option value="{{ $nome }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todos os Status</option>
                            @foreach(['DIGITANDO', 'PRÉ-ANÁLISE', 'EM ANÁLISE', 'PARA ASSINATURA', 'ASSINATURA CONCLUÍDA', 'APROVADA', 'CANCELADA', 'PAGO AO CLIENTE'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold flex items-center justify-center gap-2 transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filtrar
                    </button>

                    <input type="hidden" name="cpf" value="{{ request('cpf') }}">
                </form>
            </div>

            {{-- SEÇÃO 3: TABELA DE RESULTADOS --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">CPF</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produto</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Conveniada</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Valor</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($operacoes as $op)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                        {{ $op->codigo_operacao }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $op->nome }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $op->cpf }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $op->produto }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $op->conveniada_nome }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                        R$ {{ number_format($op->valor_requerido, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $cor = match($op->status) {
                                                'DIGITANDO' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                                'PRÉ-ANÁLISE' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                                'EM ANÁLISE' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                                'PARA ASSINATURA' => 'bg-purple-50 text-purple-700 border border-purple-100',
                                                'ASSINATURA CONCLUÍDA' => 'bg-fuchsia-100 text-fuchsia-800 border border-fuchsia-200',
                                                'APROVADA' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                                'CANCELADA' => 'bg-red-100 text-red-800 border border-red-200',
                                                'PAGO AO CLIENTE' => 'bg-indigo-600 text-white font-black',
                                                default => 'bg-blue-100 text-blue-800 border border-blue-200'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase {{ $cor }}">
                                            {{ $op->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('operacoes.show', $op->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition shadow-sm">
                                            Analisar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-400 italic">Nenhuma operação localizada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $operacoes->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
