{{--
    Tarjeta con un contador (ej: tickets pendientes).
    Uso: <x-stat-card label="Pendientes" :value="$pendingCount" icon="clock" color="yellow" />
--}}
@props([
    'label',
    'value' => 0,
    'icon' => 'ticket',
    'color' => 'slate',
])

@php
    $colors = [
        'yellow' => 'bg-yellow-50 text-yellow-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'red' => 'bg-red-50 text-red-600',
        'slate' => 'bg-slate-100 text-slate-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-slate-200 p-5 flex items-center gap-4']) }}>
    <div class="rounded-lg p-3 {{ $colors[$color] ?? $colors['slate'] }}">
        <x-icon :name="$icon" class="w-6 h-6" />
    </div>

    <div>
        <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
        <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
    </div>
</div>
