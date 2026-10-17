{{--
    Encabezado común de las páginas: breadcrumbs, título, cantidad de registros,
    subtítulo y botones de acción (slot).
    Uso:
    <x-page-header title="Marcas" subtitle="..." :count="$brands->count()"
        :breadcrumbs="['Inicio' => route('dashboard'), 'Marcas' => null]">
        <x-button onclick="openCreate()">+ Nueva marca</x-button>
    </x-page-header>
--}}
@props([
    'title',
    'subtitle' => null,
    'count' => null,
    'unit' => 'registro',
    'breadcrumbs' => [],
])

<div class="mb-6">

    @if (count($breadcrumbs))
        <x-breadcrumbs :items="$breadcrumbs" />
    @endif

    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mt-3">

        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $title }}
                </h1>

                @if (! is_null($count))
                    <span class="inline-flex items-center rounded-full bg-ucsc/10 px-3 py-1 text-xs font-semibold text-ucsc">
                        {{ $count }} {{ $count === 1 ? $unit : $unit . 's' }}
                    </span>
                @endif
            </div>

            @if ($subtitle)
                <p class="text-gray-500 mt-1">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="flex flex-wrap gap-3">
                {{ $slot }}
            </div>
        @endif

    </div>

</div>
