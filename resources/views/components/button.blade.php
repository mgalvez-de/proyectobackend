@php
    $variants = [
        'primary' => 'bg-red-600 hover:bg-red-700 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger' => 'bg-[#D12421] hover:bg-[#a91c19] text-white',
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
    'class' => 'inline-block text-center rounded-md font-medium transition ' .
        $sizes[$size] . ' ' .
        $variants[$variant]
]) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge([
    'class' => 'rounded-md font-medium transition ' .
        $sizes[$size] . ' ' .
        $variants[$variant]
]) }}>
    {{ $slot }}
</button>
@endif
