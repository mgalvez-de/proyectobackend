@php
    $variants = [
        'primary' => 'bg-blue-600 text-white',
        'secondary' => 'bg-gray-600 text-white',
        'success' => 'bg-green-600 text-white',
        'danger' => 'bg-[#D12421] text-white',
        'warning' => 'bg-yellow-400 text-gray-900',
        'info' => 'bg-cyan-500 text-white',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded shadow-sm border border-gray-200 overflow-hidden']) }}>

    @if ($image)
        <img src="{{ $image }}" alt="" class="w-full h-40 object-cover">
    @endif

    @isset($header)
        <div class="{{ $variants[$variant] }} text-center py-3">
            <h5 class="text-lg font-semibold">{{ $header }}</h5>
        </div>
    @endisset

    <div class="p-4">
        {{ $slot }}
    </div>

</div>
