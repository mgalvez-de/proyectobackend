@php
    $variants = [
        'primary' => 'bg-blue-600 text-white',
        'secondary' => 'bg-gray-600 text-white',
        'success' => 'bg-green-600 text-white',
        'danger' => 'bg-red-600 text-white',
        'warning' => 'bg-yellow-400 text-gray-900',
        'info' => 'bg-cyan-500 text-white',
    ];

    $sizes = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-5 py-3 text-base',
    ];
@endphp

<div class="overflow-x-auto rounded border border-gray-300 shadow-sm">

    <table class="min-w-full divide-y-2 divide-gray-200">

        <thead>
            <tr class="{{ $variants[$variant] }}">

                @foreach ($headers as $header)
                    <th class="{{ $sizes[$size] }} whitespace-nowrap text-left font-medium">
                        {{ $header }}
                    </th>
                @endforeach

            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">

            {{ $slot }}

        </tbody>

    </table>

</div>
