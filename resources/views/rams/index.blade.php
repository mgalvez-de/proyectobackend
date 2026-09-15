<x-app-layout>

    <x-navbar />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
            Volver al dashboard
        </a>

        <!-- encabezado -->

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mt-3 mb-6">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    RAM
                </h1>

                <p class="text-gray-500 mt-1">
                    Administración de las memorias RAM de los dispositivos.
                </p>

            </div>

            <x-button onclick="openCreate()">
                + Nueva RAM
            </x-button>

        </div>


        <!-- tabla -->

        <div class="overflow-x-auto">

            <table class="w-full min-w-[600px] text-left text-sm text-slate-600 bg-white rounded shadow-sm border border-slate-200">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">

                    <tr>

                        <th class="px-6 py-4">
                            ID
                        </th>

                        <th class="px-6 py-4">
                            Tipo
                        </th>

                        <th class="px-6 py-4">
                            Capacidad
                        </th>

                        <th class="px-6 py-4 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($rams as $ram)

                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                {{ $ram->id }}
                            </td>

                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $ram->type }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $ram->capacity }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <!-- Ver -->

                                    <button type="button"
                                        onclick='openShow(
                                            {{ $ram->id }},
                                            @json($ram->type),
                                            @json($ram->capacity)
                                        )'
                                        class="p-1.5 bg-gray-500 text-white hover:bg-gray-600 rounded transition-colors"
                                        title="Ver RAM">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                        </svg>

                                    </button>


                                    <!-- Editar -->

                                    <button type="button"
                                        onclick='openEdit(
                                            {{ $ram->id }},
                                            @json($ram->type),
                                            @json($ram->capacity)
                                        )'
                                        class="p-1.5 bg-yellow-400 text-gray-900 hover:bg-yellow-500 rounded transition-colors"
                                        title="Editar RAM">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />

                                        </svg>

                                    </button>


                                    <!-- Eliminar -->

                                    <x-button variant="danger"
                                        size="sm"
                                        type="button"
                                        onclick="openDelete({{ $ram->id }})"
                                        title="Eliminar RAM">

                                        <svg class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v3m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16" />

                                        </svg>

                                    </x-button>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        <!-- Modal crear -->

        <div id="createModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <div class="bg-red-700 px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Nueva RAM
                        </h2>

                        <p class="text-blue-100 text-sm mt-1">
                            Registra una nueva memoria RAM.
                        </p>

                    </div>

                    <x-button variant="secondary"
                        size="sm"
                        type="button"
                        onclick="closeCreate()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">

                        <svg class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </x-button>

                </div>

                <form action="{{ route('rams.store') }}" method="POST">

                    @csrf

                    <div class="px-6 py-6">

                        <div class="mb-4">

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Tipo
                            </label>

                            <input type="text"
                                name="type"
                                placeholder="Ej: DDR4"
                                required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Capacidad
                            </label>

                            <input type="text"
                                name="capacity"
                                placeholder="Ej: 16 GB"
                                required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition">

                        </div>

                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary"
                            size="sm"
                            type="button"
                            onclick="closeCreate()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Guardar RAM
                        </x-button>

                    </div>

                </form>

            </div>

        </div>


        <!-- Modal ver -->

        <div id="showModal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">

            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">

                <div class="flex justify-between items-center bg-red-700 text-white px-4 py-2 rounded-t-lg">

                    <h2 class="text-lg font-semibold">
                        Detalles de la RAM
                    </h2>

                    <x-button variant="secondary"
                        size="sm"
                        type="button"
                        onclick="closeShow()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">

                        <svg class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </x-button>

                </div>

                <div class="p-4">

                    <p>
                        <strong>ID:</strong>
                        <span id="showId"></span>
                    </p>

                    <p>
                        <strong>Tipo:</strong>
                        <span id="showType"></span>
                    </p>

                    <p>
                        <strong>Capacidad:</strong>
                        <span id="showCapacity"></span>
                    </p>

                </div>

            </div>

        </div>


        <!-- Modal editar -->

        <div id="editModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <div class="bg-red-700 px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Editar RAM
                        </h2>

                        <p class="text-blue-100 text-sm mt-1">
                            Actualizar una memoria RAM.
                        </p>

                    </div>

                    <x-button variant="secondary"
                        size="sm"
                        type="button"
                        onclick="closeEdit()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">

                        <svg class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </x-button>

                </div>

                <form id="editForm" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="px-6 py-6">

                        <div class="mb-4">

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Tipo
                            </label>

                            <input type="text"
                                name="type"
                                id="editType"
                                required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Capacidad
                            </label>

                            <input type="text"
                                name="capacity"
                                id="editCapacity"
                                required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition">

                        </div>

                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary"
                            size="sm"
                            type="button"
                            onclick="closeEdit()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Editar RAM
                        </x-button>

                    </div>

                </form>

            </div>

        </div>


        <!-- Formulario eliminar -->

        <form id="deleteForm"
            method="POST"
            class="hidden">

            @csrf
            @method('DELETE')

        </form>


        <!-- JavaScript -->

        <script>

            function openCreate() {

                document
                    .getElementById('createModal')
                    .classList
                    .remove('hidden');

            }

            function closeCreate() {

                document
                    .getElementById('createModal')
                    .classList
                    .add('hidden');

            }

            function openShow(id, type, capacity) {

                document
                    .getElementById('showModal')
                    .classList
                    .remove('hidden');

                document.getElementById('showId').textContent = id;
                document.getElementById('showType').textContent = type;
                document.getElementById('showCapacity').textContent = capacity;

            }

            function closeShow() {

                document
                    .getElementById('showModal')
                    .classList
                    .add('hidden');

            }

            function openEdit(id, type, capacity) {

                document
                    .getElementById('editModal')
                    .classList
                    .remove('hidden');

                document.getElementById('editType').value = type;
                document.getElementById('editCapacity').value = capacity;

                document.getElementById('editForm').action = '/rams/' + id;

            }

            function closeEdit() {

                document
                    .getElementById('editModal')
                    .classList
                    .add('hidden');

            }

            function openDelete(id) {

                if (confirm('¿Estás seguro de que deseas eliminar esta RAM?')) {

                    document.getElementById('deleteForm').action = '/rams/' + id;
                    document.getElementById('deleteForm').submit();

                }

            }

        </script>

    </div>

</x-app-layout>
