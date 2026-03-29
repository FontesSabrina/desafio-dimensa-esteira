<div class="card-financeiro mb-8">
    <div class="p-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Carga de Lote (Excel/CSV)</h3>
        </div>

        <form action="{{ route('operacoes.importar') }}" method="POST" enctype="multipart/form-data" class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-blue-400 transition-all bg-gray-50/50 group">
            @csrf
            <input type="file" name="planilha" id="planilha" class="hidden" accept=".csv, .xlsx, .xls" required onchange="this.form.submit()">
            <label for="planilha" class="cursor-pointer">
                <span class="block text-gray-600 mb-2 group-hover:text-blue-600 font-medium">Clique para selecionar a planilha</span>
                <span class="text-xs text-gray-400 tracking-wide uppercase">Limite: 50.000 registros</span>
            </label>
        </form>
    </div>
</div>
