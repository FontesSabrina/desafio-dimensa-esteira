<div class="card-financeiro p-6 mb-8">
    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Filtros de Busca</h3>
    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Código</label>
            <input type="text" name="codigo" value="{{ request('codigo') }}" placeholder="Ex: #123" class="input-financeiro w-full">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Produto</label>
            <input type="text" name="produto" value="{{ request('produto') }}" placeholder="Tipo" class="input-financeiro w-full">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">CPF</label>
            <input type="text" name="cpf" value="{{ request('cpf') }}" placeholder="000.000.000-00" class="input-financeiro w-full mask-cpf">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Status</label>
            <select name="status" class="input-financeiro w-full">
                <option value="">Todos os Status</option>
                @foreach(['DIGITANDO', 'PRÉ-ANÁLISE', 'EM ANÁLISE', 'PARA ASSINATURA', 'ASSINATURA CONCLUÍDA', 'APROVADA', 'CANCELADA', 'PAGO AO CLIENTE'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-import w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Filtrar
            </button>
        </div>
    </form>
</div>
