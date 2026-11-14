@php
    $user = auth()->user();

    $roleNames = [
        'admin' => 'Administrador',
        'supervisor' => 'Supervisor',
        'estudiante' => 'Estudiante',
        'tecnico' => 'Técnico',
    ];
@endphp

<nav class="sticky top-0 z-40 bg-ucsc text-white shadow-sm">
    <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6">

        <div class="flex items-center gap-3">

            <!-- ☰ abre la sidebar en el celular y la contrae en el escritorio -->
            @if ($user)
                <button type="button" title="Menú"
                    @click="window.matchMedia('(min-width: 1024px)').matches ? (collapsed = ! collapsed) : (sidebarOpen = true)"
                    class="rounded-md p-2 text-white/90 hover:bg-white/10 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            @endif

            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo-ucsc.png') }}" alt="UCSC" class="h-8 w-8">
                <span class="text-lg font-semibold">UCSC</span>
            </a>
        </div>

        <div class="flex items-center gap-1 text-sm">

            <!-- Usuario conectado -->
            @if ($user)
                <span class="hidden sm:block px-3 text-right leading-tight">
                    <span class="block font-medium">{{ $user->name }}</span>
                    <span class="block text-xs text-red-100">{{ $roleNames[$user->rol] ?? $user->rol }}</span>
                </span>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-md px-3 py-2 font-medium hover:bg-white/10 transition-colors">
                    Cerrar sesión
                </button>
            </form>

        </div>

    </div>
</nav>
