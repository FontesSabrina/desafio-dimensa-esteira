<x-app-layout>
    <div class="flex min-h-screen bg-gray-50">
        {{-- Menu Lateral --}}
        <x-sidebar />

        <main class="flex-1">
            <header class="bg-white shadow-sm border-b border-gray-200 py-4 px-8 flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestão de Esteira Ativa</h2>
                <div class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</div>
            </header>

            <div class="p-8">
                <div class="max-w-7xl mx-auto">

                    {{-- Área de Importação --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <h3 class="text-gray-700 font-bold uppercase tracking-widest text-xs">Importar Novo Arquivo (Excel/CSV)</h3>
                        </div>

                        <form action="{{ route('operacoes.importar') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4">
                            @csrf
                            <div class="flex-1 w-full">
                                <input type="file" name="planilha" id="planilha" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-dashed border-gray-300 rounded-lg p-1">
                            </div>
                            <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-all shadow-md flex items-center justify-center gap-2">
                                <span>PROCESSAR DADOS</span>
                            </button>
                        </form>
                        <p class="mt-2 text-xs text-gray-400">* Formatos aceitos: .xlsx, .xls e .csv padrão.</p>
                    </div>

                    {{-- Mensagens de Feedback --}}
                    @if(session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Listagem de Operações --}}
                    <div class="card-financeiro bg-white shadow-sm rounded-lg overflow-hidden border border-gray-100">
                        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                            <h3 class="font-bold text-gray-700 uppercase tracking-widest text-xs">Monitoramento de Propostas</h3>

                            {{-- Botão de Relatório --}}
                            <a href="{{ route('operacoes.exportar') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition-colors bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                EXPORTAR RELATÓRIO
                            </a>
                        </div>

                        {{-- Filtros --}}
                        <div class="p-6 bg-white border-b border-gray-50">
                            @include('operacoes.partials._filters')
                        </div>

                        {{-- Tabela (Onde o botão "Analisar" vai aparecer) --}}
                        <div class="overflow-x-auto">
                            @include('operacoes.partials._table', ['operacoes' => $operacoes])
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</x-app-layout>
