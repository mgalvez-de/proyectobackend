<x-app-layout>
    @include('components.navbar')
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">


            <!--encabezado-->

            <div>
                <h1 class="text-3xl font-bold text-gray-800 mt-6">
                    Almacenamiento
                </h1>

                <p class="text-gray-500 mt-1">
                    Administración de las capacidades de almacenamiento de dispositivos del sistema.
                </p>
            </div>

            <x-button onclick="openCreate()">
                + Nuevo almacenamiento
            </x-button>

        </div>


        <!-- tabla -->

        <div class="overflow-x-auto">

            <table class="w-full bg-white min-w-[600px] text-left text-sm text-slate-600">

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

                    @foreach ($storages as $storage)
                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                {{ $storage->id }}
                            </td>

                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $storage->type }}
                            </td>

                            <td class="px-6 py-4 text-slate-800">
                                {{ $storage->capacity }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <!-- Ver -->

                                    <button type="button"
                                        onclick='openShow({{ $storage->id }}, "{{ $storage->type }}", "{{ $storage->capacity }}")'
                                        class="p-1.5 bg-gray-500 text-white hover:bg-gray-600 rounded transition-colors"
                                        title="Ver almacenamiento">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                    </button>


                                    <!-- boton editar -->

                                    <button
                                        onclick='openEdit({{ $storage->id }}, "{{ $storage->type }}", "{{ $storage->capacity }}")'
                                        class="p-1.5 bg-yellow-400 text-gray-900 hover:bg-yellow-500 rounded transition-colors"
                                        title="Editar almacenamiento">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>


                                    <!-- boton eliminar -->

                                    <x-button variant="danger" size="sm" type="button"
                                        onclick="openDelete({{ $storage->id }})" title="Eliminar almacenamiento">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v3m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </x-button>

                                </div>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>


        <!-- Modal para crear un almacenamiento -->

        <div id="createModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Encabezado -->

                <div class="bg-red-700 px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Nuevo almacenamiento
                        </h2>

                        <p class="text-blue-100 text-sm mt-1">
                            Registra una nueva capacidad de almacenamiento.
                        </p>

                    </div>

                    <x-button variant="secondary" size="sm" type="button" onclick="closeCreate()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-button>

                </div>


                <!-- Formulario -->

                <form action="{{ route('storages.store') }}" method="POST">

                    @csrf

                    <div class="px-6 py-6 space-y-4">

                        <div>
                            <label for="type" class="block text-sm font-semibold text-slate-700 mb-2">
                                Tipo
                            </label>

                            <input type="text" name="type" id="type" placeholder="Ej: SSD M.2"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-blue-100
                    focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-slate-700 mb-2">
                                Capacidad
                            </label>

                            <input type="text" name="capacity" id="capacity" placeholder="Ej: 500 GB"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-blue-100
                    focus:border-blue-500 transition">
                        </div>

                    </div>


                    <!-- botones para modals -->

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeCreate()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Guardar almacenamiento
                        </x-button>

                    </div>

                </form>

            </div>

        </div>

        <!-- Modal para ver un almacenamiento -->
        <div id="showModal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center bg-red-700 text-white px-4 py-2 rounded-t-lg">
                    <h2 class="text-lg font-semibold">Detalles del almacenamiento</h2>

                    <x-button variant="secondary" size="sm" type="button" onclick="closeShow()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-button>

                </div>
                <div class="p-4">
                    <p><strong>ID:</strong> <span id="showId"></span></p>
                    <p><strong>Tipo:</strong> <span id="showType"></span></p>
                    <p><strong>Capacidad:</strong> <span id="showCapacity"></span></p>
                </div>
            </div>
        </div>

        <!--Modal para editar un almacenamiento -->
        <div
            id="editModal"class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Encabezado -->

                <div class="bg-red-700 px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Editar almacenamiento
                        </h2>

                        <p class="text-blue-100 text-sm mt-1">
                            Actualizar una capacidad de almacenamiento
                        </p>

                    </div>

                    <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-button>

                </div>


                <!-- Formulario -->

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-6 space-y-4">

                        <div>
                            <label for="editType" class="block text-sm font-semibold text-slate-700 mb-2">
                                Tipo
                            </label>

                            <input type="text" name="type" id="editType" placeholder="Ej: SSD M.2"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-blue-100
                    focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="editCapacity" class="block text-sm font-semibold text-slate-700 mb-2">
                                Capacidad
                            </label>

                            <input type="text" name="capacity" id="editCapacity" placeholder="Ej: 500 GB"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-blue-100
                    focus:border-blue-500 transition">
                        </div>

                    </div>


                    <!-- Botones -->

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Editar almacenamiento
                        </x-button>

                    </div>

                </form>

            </div>

        </div>

        <!-- formulario para eliminar -->
        <form id="deleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        <!-- JavaScript -->

        <script>
            function openCreate() {
                document
                    .getElementById('createModal')
                    .classList.remove('hidden');
            }

            function closeCreate() {
                document
                    .getElementById('createModal')
                    .classList.add('hidden');
            }

            function openShow(id, type, capacity) {
                document.getElementById('showModal').classList.remove('hidden');

                document.getElementById('showId').textContent = id;
                document.getElementById('showType').textContent = type;
                document.getElementById('showCapacity').textContent = capacity;
            }

            function closeShow() {
                document
                    .getElementById('showModal')
                    .classList.add('hidden');
            }

            function openEdit(id, type, capacity) {
                document.getElementById('editModal').classList.remove('hidden');

                document.getElementById('editType').value = type;
                document.getElementById('editCapacity').value = capacity;

                document.getElementById('editForm').action = '/storages/' + id;
            }

            function closeEdit() {
                document
                    .getElementById('editModal')
                    .classList.add('hidden');
            }

            function openDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el almacenamiento permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D12421',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').action = '/storages/' + id;
                        document.getElementById('deleteForm').submit();
                    }
                });
            }

            @if (session('success'))
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            @endif
        </script>
    </div>
</x-app-layout>
