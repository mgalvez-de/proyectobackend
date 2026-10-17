@php
    $variants = [
        'primary' => 'bg-ucsc hover:bg-ucsc-dark text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger' => 'bg-ucsc hover:bg-ucsc-dark text-white',
        'warning' => 'bg-yellow-400 hover:bg-yellow-500 text-gray-900',
        'info' => 'bg-cyan-500 hover:bg-cyan-600 text-white',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-3 text-base',
    ];
@endphp

@if ($href)
<a href="{{ $href }}" {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center gap-2 text-center rounded-md font-medium shadow-sm transition ' .
        $sizes[$size] . ' ' .
        $variants[$variant]
]) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center gap-2 rounded-md font-medium shadow-sm transition focus:outline-none focus-visible:ring-2 focus-visible:ring-ucsc focus-visible:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed ' .
        $sizes[$size] . ' ' .
        $variants[$variant]
]) }}>
    {{ $slot }}
</button>
@endif
