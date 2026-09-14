<x-app-layout>

    <x-navbar />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Volver al dashboard
        </a>

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mt-3 mb-6">

            <h1 class="text-3xl font-bold text-gray-800">
                Departamento de Informática
            </h1>

            <x-button href="#" variant="danger">
                + Añadir dispositivo
            </x-button>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[600px] text-left text-sm text-slate-600 bg-white rounded shadow-sm border border-slate-200">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4">Marca</th>
                        <th class="px-6 py-4">Modelo</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>
