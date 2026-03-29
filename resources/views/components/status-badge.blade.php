@props(['status'])

@php
    $isPago = $status === 'PAGO AO CLIENTE';
    $classes = $isPago ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800';
    $dotClasses = $isPago ? 'bg-emerald-500' : 'bg-blue-500';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold shadow-sm {{ $classes }}">
    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotClasses }}"></span>
    {{ $status }}
</span>
