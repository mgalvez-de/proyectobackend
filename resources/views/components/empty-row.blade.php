{{-- Fila para tablas vacías. Uso dentro de @forelse ... @empty: <x-empty-row colspan="4" message="..." /> --}}
@props([
    'colspan' => 1,
    'message' => 'No hay registros.',
    'hint' => null,
])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-14">
        <div class="flex flex-col items-center justify-center text-center">

            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-3">
                <x-icon name="inbox" class="w-7 h-7" />
            </div>

            <p class="font-medium text-slate-600">
                {{ $message }}
            </p>

            @if ($hint)
                <p class="text-sm text-slate-400 mt-1">
                    {{ $hint }}
                </p>
            @endif

        </div>
    </td>
</tr>
