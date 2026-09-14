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

                <x-card variant="danger" image="https://facultades.ucsc.cl/content/uploads/sites/25/2023/06/IngEjInformatica-1.jpg">
                    <x-slot name="header">Informática</x-slot>

                    <p class="text-gray-600 text-sm mb-4">
                        Inventario de equipos del departamento de Informática.
                    </p>

                    <x-button href="#" variant="danger" class="w-full">
                        Ver todo
                    </x-button>
                </x-card>

                <x-card variant="danger" image="https://upload.wikimedia.org/wikipedia/commons/d/df/Biblioteca_Barzio.jpg">
                    <x-slot name="header">Biblioteca</x-slot>

                    <p class="text-gray-600 text-sm mb-4">
                        Inventario de equipos disponibles en la Biblioteca.
                    </p>

                    <x-button href="#" variant="danger" class="w-full">
                        Ver todo
                    </x-button>
                </x-card>

                <x-card variant="danger" image="https://kdoce.cl/wp-content/uploads/2017/03/01-2.jpg">
                    <x-slot name="header">Sala de Computación</x-slot>

                    <p class="text-gray-600 text-sm mb-4">
                        Inventario de equipos de la Sala de Computación.
                    </p>

                    <x-button href="#" variant="danger" class="w-full">
                        Ver todo
                    </x-button>
                </x-card>

            </div>

        </div>
    </div>

</x-app-layout>
