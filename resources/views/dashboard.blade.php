<x-app-layout>

    <x-navbar />

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-2xl font-bold text-gray-800 mb-1">
                Bienvenido, {{ auth()->user()->name }}
            </h2>
            <p class="text-gray-600 mb-8">
                Selecciona un departamento para ver su inventario de dispositivos.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <a href="{{ route('informatica.index') }}"
                    class="group relative block h-80 rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <img src="https://facultades.ucsc.cl/content/uploads/sites/25/2023/06/IngEjInformatica-1.jpg"
                        alt="Informática"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/40 transition-colors duration-300 group-hover:bg-black/55">
                    </div>
                    <div class="relative h-full flex items-center justify-center px-4">
                        <h3 class="text-white text-2xl font-bold text-center drop-shadow-lg">Informática</h3>
                    </div>
                </a>

                <a href="#"
                    class="group relative block h-80 rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/df/Biblioteca_Barzio.jpg"
                        alt="Biblioteca"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/40 transition-colors duration-300 group-hover:bg-black/55">
                    </div>
                    <div class="relative h-full flex items-center justify-center px-4">
                        <h3 class="text-white text-2xl font-bold text-center drop-shadow-lg">Biblioteca</h3>
                    </div>
                </a>

                <a href="#"
                    class="group relative block h-80 rounded-lg overflow-hidden shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <img src="https://kdoce.cl/wp-content/uploads/2017/03/01-2.jpg" alt="Sala de Computación"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/40 transition-colors duration-300 group-hover:bg-black/55">
                    </div>
                    <div class="relative h-full flex items-center justify-center px-4">
                        <h3 class="text-white text-2xl font-bold text-center drop-shadow-lg">Sala de Computación</h3>
                    </div>
                </a>

            </div>

        </div>
    </div>

</x-app-layout>
