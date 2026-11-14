{{--
    Sidebar con las secciones según el rol del usuario.
    - Escritorio: fija a la izquierda, se contrae a solo íconos (se recuerda en el navegador).
    - Celular: panel que se abre con el botón ☰ del navbar.
    El estado (sidebarOpen / collapsed) vive en el x-data del layout.
--}}
@php
    $user = auth()->user();

    // enlace del menú: [url, texto, ícono, si es la página actual]
    $link = fn ($route, $label, $icon, $params = []) => [
        'url' => route($route, $params),
        'label' => $label,
        'icon' => $icon,
        'active' => request()->routeIs(str_replace('.index', '', $route) . '*'),
    ];

    // departamento abierto en la URL (sirve para marcar el activo)
    $currentDepartment = request()->route('department');
    $currentDepartmentId = is_object($currentDepartment) ? $currentDepartment->id : $currentDepartment;

    $sections = [];

    if ($user->rol === 'admin') {
        $sections['General'] = [$link('dashboard', 'Inicio', 'home')];

        $sections['Departamentos'] = \App\Models\Department::orderBy('id')->get()->map(fn ($department) => [
            'url' => route('departments.show', $department),
            'label' => $department->displayName(),
            'icon' => 'computer',
            'active' => (string) $currentDepartmentId === (string) $department->id,
        ])->all();

        $sections['Hardware y especificaciones'] = [
            $link('brands.index', 'Marcas', 'tag'),
            $link('device-types.index', 'Tipos de dispositivo', 'computer'),
            $link('rams.index', 'Capacidad de RAM', 'server'),
            $link('storages.index', 'Almacenamiento', 'database'),
            $link('processors.index', 'Procesadores', 'chip'),
            $link('hardware-components.index', 'Componentes', 'squares-plus'),
        ];

        $sections['Disponibilidad'] = [
            $link('device-statuses.index', 'Estados de equipo', 'check-badge'),
        ];

        $sections['Soporte y tickets'] = [
            $link('hardware-failures.index', 'Fallas de hardware', 'wrench'),
            $link('software-failures.index', 'Fallas de software', 'code'),
            $link('technicians.index', 'Técnicos', 'user'),
        ];

        $sections['Administración'] = [
            $link('users.index', 'Usuarios', 'user-plus'),
        ];

        $sections['Desarrollo'] = [
            $link('api-docs.index', 'Documentación API', 'book'),
        ];
    } elseif ($user->rol === 'supervisor' && $user->department_id) {
        $sections['Mi departamento'] = [
            [
                'url' => route('departments.show', $user->department_id),
                'label' => 'Dispositivos',
                'icon' => 'computer',
                'active' => request()->routeIs('departments.show'),
            ],
            [
                'url' => route('departments.history', $user->department_id),
                'label' => 'Historial',
                'icon' => 'clock',
                'active' => request()->routeIs('departments.history'),
            ],
        ];
    } elseif ($user->rol === 'tecnico') {
        $sections['Mi trabajo'] = [$link('my-tickets.index', 'Mis tickets', 'wrench')];
    } else {
        $sections['General'] = [$link('dashboard', 'Inicio', 'home')];
    }
@endphp

<!-- ===================== ESCRITORIO ===================== -->
<aside x-bind:class="collapsed ? 'w-[4.5rem]' : 'w-64'"
    class="hidden lg:flex w-64 shrink-0 flex-col sticky top-16 h-[calc(100vh-4rem)] bg-white border-r border-slate-200 transition-[width] duration-200">

    <div class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-4"
        x-init="$nextTick(() => $el.querySelector('[aria-current=page]')?.scrollIntoView({ block: 'nearest' }))">
        <x-sidebar-links :sections="$sections" />
    </div>
</aside>

<!-- ===================== CELULAR ===================== -->
<div x-show="sidebarOpen" x-cloak class="lg:hidden fixed inset-0 z-50" role="dialog" aria-modal="true">

    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/50"></div>

    <div x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        class="relative flex h-full w-72 max-w-[85%] flex-col bg-white shadow-xl">

        <div class="flex h-16 items-center justify-between bg-ucsc px-4 text-white">
            <span class="flex items-center gap-2 font-semibold">
                <img src="{{ asset('images/logo-ucsc.png') }}" alt="UCSC" class="h-8 w-8">
                Menú
            </span>
            <button type="button" @click="sidebarOpen = false" title="Cerrar menú"
                class="rounded-md p-1.5 text-white/80 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-5" @click="if ($event.target.closest('a')) sidebarOpen = false">
            <x-sidebar-links :sections="$sections" mobile />
        </div>
    </div>
</div>
