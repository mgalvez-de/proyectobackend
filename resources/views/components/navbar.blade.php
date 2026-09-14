<nav class="bg-[#D12421] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo-ucsc.png') }}" alt="UCSC" class="h-8 w-8">
                <span class="text-lg font-semibold">UCSC</span>
            </a>

            <div class="flex items-center gap-4 flex-wrap justify-end text-sm">

                <a href="{{ route('dashboard') }}" class="hover:text-red-100">Inicio</a>

                @if (auth()->user()?->rol === 'admin')
                    <details class="relative">
                        <summary class="cursor-pointer list-none hover:text-red-100">Agregar opciones</summary>

                        <div class="absolute right-0 mt-2 w-64 bg-white text-gray-800 rounded shadow-lg border border-gray-200 z-10 p-3 space-y-3">

                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Hardware y Especificaciones</p>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Marcas</a>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Tipos de Dispositivo</a>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Capacidad de RAM</a>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Almacenamiento</a>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Procesadores</a>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Disponibilidad</p>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Estados de Equipo</a>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Soporte y Tickets</p>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Reportes de hardware</a>
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Reportes de software</a>
                            </div>

                            <div class="border-t border-gray-200 pt-2">
                                <a href="#" class="block px-2 py-1 rounded hover:bg-gray-100">Registrar usuario</a>
                            </div>

                        </div>
                    </details>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-100">Cerrar sesión</button>
                </form>

            </div>

        </div>
    </div>
</nav>
