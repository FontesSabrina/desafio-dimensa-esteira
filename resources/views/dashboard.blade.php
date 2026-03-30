<x-app-layout>
    <div class="flex min-h-screen bg-gray-50">
        <x-sidebar />

        <main class="flex-1">
            <header class="bg-white shadow-sm border-b border-gray-100 py-4 px-8 flex justify-between items-center">
                <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-tighter">
                    Gestão de Esteira Ativa
                </h2>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 px-3 py-1 rounded-full">
                    {{ now()->format('d/m/Y') }}
                </div>
            </header>

            <div class="p-8">
                <div class="max-w-7xl mx-auto">

                    <div class="card-financeiro !mb-6">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <h3 class="card-title !mb-0">Importar Novo Arquivo (Excel/CSV)</h3>
                        </div>

                        <form action="{{ route('operacoes.importar') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4">
                            @csrf
                            <div class="flex-1 w-full import-zone">
                                <input type="file" name="planilha" id="planilha" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-6 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition-all cursor-pointer">
                            </div>
                            <button type="submit" class="btn-import h-[46px]">
                                <span>PROCESSAR DADOS</span>
                            </button>
                        </form>
                        <p class="mt-3 text-[10px] font-bold text-gray-400 italic">* Formatos aceitos: .xlsx, .xls e .csv padrão.</p>
                    </div>

                    @if(session('status'))
                        <div class="alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-danger">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-financeiro !p-0">
                        <div class="px-8 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                            <h3 class="card-title !mb-0">Monitoramento de Propostas</h3>

                            <a href="{{ route('operacoes.exportar') }}" class="badge-status bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all py-2 px-4 gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                EXPORTAR RELATÓRIO
                            </a>
                        </div>

                        <div class="p-8 border-b border-gray-50 bg-white">
                            @include('operacoes.partials._filters')
                        </div>

                        <div class="overflow-x-auto">
                            @include('operacoes.partials._table', ['operacoes' => $operacoes])
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</x-app-layout>
