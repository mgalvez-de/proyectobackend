@php
    $user = auth()->user();

    $roleNames = [
        'admin' => 'Administrador',
        'supervisor' => 'Supervisor',
        'estudiante' => 'Estudiante',
    ];

    // Secciones del menú "Agregar opciones" (solo admin)
    $menu = [
        'Hardware y Especificaciones' => [
            ['route' => 'brands', 'label' => 'Marcas', 'icon' => 'tag'],
            ['route' => 'device-types', 'label' => 'Tipos de Dispositivo', 'icon' => 'computer'],
            ['route' => 'rams', 'label' => 'Capacidad de RAM', 'icon' => 'server'],
            ['route' => 'storages', 'label' => 'Almacenamiento', 'icon' => 'database'],
            ['route' => 'processors', 'label' => 'Procesadores', 'icon' => 'chip'],
        ],
        'Disponibilidad' => [
            ['route' => 'device-statuses', 'label' => 'Estados de Equipo', 'icon' => 'check-badge'],
        ],
        'Soporte y Tickets' => [
            ['route' => 'hardware-failures', 'label' => 'Reportes de hardware', 'icon' => 'wrench'],
            ['route' => 'software-failures', 'label' => 'Reportes de software', 'icon' => 'code'],
        ],
    ];

    $linkBase = 'rounded-md px-3 py-2 font-medium transition-colors';
@endphp

<nav class="bg-ucsc text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between min-h-[4rem] py-2 gap-x-4 gap-y-2 flex-wrap">

            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo-ucsc.png') }}" alt="UCSC" class="h-8 w-8">
                <span class="text-lg font-semibold">UCSC</span>
            </a>

            <div class="flex items-center gap-1 flex-wrap justify-end text-sm">

                @if ($user?->rol === 'admin')
                    <!-- Menú de mantenedores -->
                    <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false"
                        class="relative">

                        <button type="button" @click="open = ! open"
                            class="inline-flex items-center gap-1 rounded-md bg-white px-3 py-2 font-medium text-ucsc hover:bg-gray-100 transition-colors">
                            Agregar opciones
                            <x-icon name="chevron-down" class="w-4 h-4 transition-transform"
                                x-bind:class="open && 'rotate-180'" />
                        </button>

                        <div x-show="open" x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute right-0 mt-2 w-64 bg-white text-gray-800 rounded-lg shadow-lg ring-1 ring-black/5 z-50 p-2">

                            @foreach ($menu as $section => $items)
                                <div class="{{ $loop->first ? '' : 'mt-2 pt-2 border-t border-gray-100' }}">
                                    <p class="px-2 pb-1 text-xs font-semibold text-gray-400 uppercase">
                                        {{ $section }}
                                    </p>

                                    @foreach ($items as $item)
                                        @php $active = request()->routeIs($item['route'] . '.*'); @endphp
                                        <a href="{{ route($item['route'] . '.index') }}"
                                            class="flex items-center gap-2.5 px-2 py-1.5 rounded-md transition-colors {{ $active ? 'bg-ucsc/10 text-ucsc font-medium' : 'hover:bg-gray-100' }}">
                                            <x-icon :name="$item['icon']"
                                                class="w-4 h-4 {{ $active ? 'text-ucsc' : 'text-gray-400' }}" />
                                            {{ $item['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="mt-2 pt-2 border-t border-gray-100">
                                @php $active = request()->routeIs('users.*'); @endphp
                                <a href="{{ route('users.index') }}"
                                    class="flex items-center gap-2.5 px-2 py-1.5 rounded-md transition-colors {{ $active ? 'bg-ucsc/10 text-ucsc font-medium' : 'hover:bg-gray-100' }}">
                                    <x-icon name="user-plus" class="w-4 h-4 {{ $active ? 'text-ucsc' : 'text-gray-400' }}" />
                                    Registrar usuario
                                </a>
                            </div>

                        </div>
                    </div>
                @endif

                <!-- Usuario conectado -->
                @if ($user)
                    <span class="hidden md:block px-3 text-right leading-tight">
                        <span class="block font-medium">{{ $user->name }}</span>
                        <span class="block text-xs text-red-100">{{ $roleNames[$user->rol] ?? $user->rol }}</span>
                    </span>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="{{ $linkBase }} hover:bg-white/10">
                        Cerrar sesión
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>
