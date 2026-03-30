<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
                    Gestão de Esteira Ativa
                </h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Monitoramento em Tempo Real</p>
            </div>
            <a href="{{ route('operacoes.exportar', request()->all()) }}" class="btn-import bg-emerald-500 hover:bg-emerald-600 border-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Gerar Relatório
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- SEÇÃO DE IMPORTAÇÃO --}}
            <div class="card-financeiro !p-1 border-2 border-dashed border-indigo-200 bg-indigo-50/50">
                <div class="bg-white p-6 rounded-[1.4rem]">
                    <form action="{{ route('operacoes.importar') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center justify-between gap-6">
                        @csrf
                        <div class="flex items-center gap-4">
                            <div class="bg-indigo-100 p-3 rounded-2xl text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-gray-800 uppercase italic">Importação de Lote</h4>
                                <p class="text-xs text-gray-400">Excel ou CSV</p>
                            </div>
                        </div>
                        <input type="file" name="planilha" class="block text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" required>
                        <button type="submit" class="btn-filter md:w-auto px-8">Processar Dados</button>
                    </form>
                </div>
            </div>

            {{-- SEÇÃO DE FILTROS --}}
            @include('operacoes.partials._filters')

            {{-- SEÇÃO DA TABELA --}}
            <div class="card-financeiro !p-0">
                @include('operacoes.partials._table')
            </div>

        </div>
    </div>
</x-app-layout>
