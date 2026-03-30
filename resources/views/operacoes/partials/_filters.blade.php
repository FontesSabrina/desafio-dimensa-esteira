<div class="card-financeiro">
    <h3 class="card-title">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
        </svg>
        Filtros de Busca
    </h3>

    <form method="GET" action="{{ route('operacoes.index') }}" class="grid-filters">
        <div class="filter-group">
            <label class="filter-label">Código</label>
            <input type="text" name="codigo" value="{{ request('codigo') }}" placeholder="#0000" class="input-financeiro">
        </div>

        <div class="filter-group">
            <label class="filter-label">Produto</label>
            <input type="text" name="produto" value="{{ request('produto') }}" placeholder="Ex: Consignado" class="input-financeiro">
        </div>

        <div class="filter-group">
            <label class="filter-label">CPF Cliente</label>
            <input type="text" name="cpf" value="{{ request('cpf') }}" placeholder="000.000.000-00" class="input-financeiro mask-cpf">
        </div>

        <div class="filter-group">
            <label class="filter-label">Conveniada</label>
            <input type="text" name="conveniada" value="{{ request('conveniada') }}" placeholder="Buscar..." class="input-financeiro">
        </div>

        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="input-financeiro appearance-none">
                <option value="">TODOS OS STATUS</option>
                @foreach(['DIGITANDO', 'PRÉ-ANÁLISE', 'EM ANÁLISE', 'PARA ASSINATURA', 'ASSINATURA CONCLUÍDA', 'APROVADA', 'CANCELADA', 'PAGO AO CLIENTE'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <button type="submit" class="btn-filter">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Filtrar
            </button>
        </div>
    </form>
</div>
