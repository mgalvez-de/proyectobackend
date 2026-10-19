{{-- Ruta de navegación. Uso: <x-breadcrumbs :items="['Inicio' => route('dashboard'), 'Marcas' => null]" /> --}}
@props(['items' => []])

<nav aria-label="Ruta de navegación" class="text-sm">
    <ol class="flex flex-wrap items-center gap-1.5 text-gray-500">
        @foreach ($items as $label => $url)
            <li class="flex items-center gap-1.5">
                @unless ($loop->first)
                    <x-icon name="chevron-right" class="w-3.5 h-3.5 text-gray-400" />
                @endunless

                @if ($url && ! $loop->last)
                    <a href="{{ $url }}" class="inline-flex items-center gap-1 hover:text-ucsc transition-colors">
                        @if ($label === 'Inicio')
                            <x-icon name="home" class="w-4 h-4" />
                        @endif
                        {{ $label }}
                    </a>
                @else
                    <span class="font-medium text-gray-700" aria-current="page">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
