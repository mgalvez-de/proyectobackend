{{-- Etiqueta de color. Uso: <x-badge color="red">Crítica</x-badge> --}}
@props(['color' => 'gray', 'dot' => false])

@php
    $colors = [
        'gray' => 'bg-gray-100 text-gray-700 ring-gray-500/20',
        'green' => 'bg-green-50 text-green-700 ring-green-600/20',
        'yellow' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/30',
        'orange' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
        'red' => 'bg-red-50 text-red-700 ring-red-600/20',
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'ucsc' => 'bg-ucsc/10 text-ucsc ring-ucsc/20',
    ];

    $dots = [
        'gray' => 'bg-gray-400',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'orange' => 'bg-orange-500',
        'red' => 'bg-red-500',
        'blue' => 'bg-blue-500',
        'ucsc' => 'bg-ucsc',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset ' . ($colors[$color] ?? $colors['gray'])]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dots[$color] ?? $dots['gray'] }}"></span>
    @endif
    {{ $slot }}
</span>
