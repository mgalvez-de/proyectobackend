<x-app-layout>

    <x-navbar />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
            Volver al dashboard
        </a>

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mt-3 mb-6">

            <h1 class="text-3xl font-bold text-gray-800">
                Departamento de {{ $department->name }}
            </h1>

            <x-button type="button" variant="danger" onclick="openCreate()">
                + Añadir dispositivo
            </x-button>

        </div>

        <!-- TABLA -->

        <div class="overflow-x-auto">

            <table
                class="w-full min-w-[700px] text-left text-sm text-slate-600 bg-white rounded shadow-sm border border-slate-200">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">

                    <tr>

                        <th class="px-6 py-4">ID</th>

                        <th class="px-6 py-4">Nombre</th>

                        <th class="px-6 py-4">Marca</th>

                        <th class="px-6 py-4">Almacenamiento</th>

                        <th class="px-6 py-4">RAM</th>

                        <th class="px-6 py-4 text-center">Acciones</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($devices as $device)
                        <tr>

                            <td class="px-6 py-4">
                                {{ $device->id }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $device->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $device->brand->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $device->storage->type }} - {{ $device->storage->capacity }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $device->ram->type }} - {{ $device->ram->capacity }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <!-- VER -->

                                    <button type="button"
                                        onclick='openShow(
                                            {{ $device->id }},
                                            @json($device->name),
                                            @json($device->brand->name),
                                            @json($device->storage->type . ' - ' . $device->storage->capacity),
                                            @json($device->ram->type . ' - ' . $device->ram->capacity),
                                            @json($device->department->name)
                                        )'
                                        class="p-1.5 bg-gray-500 text-white hover:bg-gray-600 rounded transition-colors"
                                        title="Ver dispositivo">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                        </svg>

                                    </button>


                                    <!-- EDITAR -->

                                    <button type="button"
                                        onclick='openEdit(
                                            {{ $device->id }},
                                            @json($device->name),
                                            {{ $device->brand_id }},
                                            {{ $device->storage_id }},
                                            {{ $device->ram_id }}
                                        )'
                                        class="p-1.5 bg-yellow-400 text-gray-900 hover:bg-yellow-500 rounded transition-colors"
                                        title="Editar dispositivo">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />

                                        </svg>

                                    </button>


                                    <!-- ELIMINAR -->

                                    <x-button variant="danger" size="sm" type="button"
                                        onclick="openDelete({{ $device->id }})" title="Eliminar dispositivo">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v3m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16" />

                                        </svg>

                                    </x-button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No hay dispositivos registrados.
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <!-- MODAL AGREGAR -->

    <div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">

        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">

            <div class="flex justify-between items-center bg-red-700 text-white px-4 py-2 rounded-t-lg">

                <h2 class="text-lg font-semibold">
                    Añadir dispositivo
                </h2>

                <x-button variant="secondary" size="sm" type="button" onclick="closeCreate()"
                    class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </x-button>

            </div>

            <div class="p-4">

                <form method="POST" action="{{ route('devices.store') }}">

                    @csrf

                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
                        </label>

                        <input type="text" name="name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Ej: PC-Informática-01">

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Marca
                        </label>

                        <select name="brand_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">

                            <option value="">
                                Seleccione una marca
                            </option>

                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">
                                    {{ $brand->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Almacenamiento
                        </label>

                        <select name="storage_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">

                            <option value="">
                                Seleccione almacenamiento
                            </option>

                            @foreach ($storages as $storage)
                                <option value="{{ $storage->id }}">
                                    {{ $storage->type }} - {{ $storage->capacity }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RAM
                        </label>

                        <select name="ram_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">

                            <option value="">
                                Seleccione RAM
                            </option>

                            @foreach ($rams as $ram)
                                <option value="{{ $ram->id }}">
                                    {{ $ram->type }} - {{ $ram->capacity }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <input type="hidden" name="department_id" value="{{ $department->id }}">


                    <div class="flex justify-end gap-2 mt-6">

                        <x-button type="button" variant="secondary" size="sm" onclick="closeCreate()">

                            Cancelar

                        </x-button>

                        <x-button type="submit" variant="danger" size="sm">

                            Guardar

                        </x-button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- MODAL VER -->

    <div id="showModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">

        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">

            <div class="flex justify-between items-center bg-red-700 text-white px-4 py-2 rounded-t-lg">

                <h2 class="text-lg font-semibold">
                    Detalles del dispositivo
                </h2>

                <x-button variant="secondary" size="sm" type="button" onclick="closeShow()"
                    class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                    <strong>Nombre:</strong>
                    <span id="showName"></span>
                </p>

                <p>
                    <strong>Marca:</strong>
                    <span id="showBrand"></span>
                </p>

                <p>
                    <strong>Almacenamiento:</strong>
                    <span id="showStorage"></span>
                </p>

                <p>
                    <strong>RAM:</strong>
                    <span id="showRam"></span>
                </p>

                <p>
                    <strong>Departamento:</strong>
                    <span id="showDepartment"></span>
                </p>

            </div>

        </div>

    </div>


    <!-- MODAL EDITAR -->

    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">

        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">

            <div class="flex justify-between items-center bg-red-700 text-white px-4 py-2 rounded-t-lg">

                <h2 class="text-lg font-semibold">
                    Editar dispositivo
                </h2>

                <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()"
                    class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </x-button>

            </div>

            <div class="p-4">

                <form id="editForm" method="POST">

                    @csrf
                    @method('PUT')


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
                        </label>

                        <input type="text" id="editName" name="name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Marca
                        </label>

                        <select id="editBrand" name="brand_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">

                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">
                                    {{ $brand->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Almacenamiento
                        </label>

                        <select id="editStorage" name="storage_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">

                            @foreach ($storages as $storage)
                                <option value="{{ $storage->id }}">
                                    {{ $storage->type }} - {{ $storage->capacity }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RAM
                        </label>

                        <select id="editRam" name="ram_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">

                            @foreach ($rams as $ram)
                                <option value="{{ $ram->id }}">
                                    {{ $ram->type }} - {{ $ram->capacity }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <input type="hidden" name="department_id" value="{{ $department->id }}">


                    <div class="flex justify-end gap-2 mt-6">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()">

                            Cancelar

                        </x-button>

                        <x-button type="submit" variant="danger" size="sm">

                            Editar dispositivo

                        </x-button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- FORMULARIO ELIMINAR -->

    <form id="deleteForm" method="POST" class="hidden">

        @csrf
        @method('DELETE')

    </form>


    <!-- JAVASCRIPT -->

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


        function openShow(id, name, brand, storage, ram, department) {

            document
                .getElementById('showModal')
                .classList
                .remove('hidden');

            document.getElementById('showId').textContent = id;

            document.getElementById('showName').textContent = name;

            document.getElementById('showBrand').textContent = brand;

            document.getElementById('showStorage').textContent = storage;

            document.getElementById('showRam').textContent = ram;

            document.getElementById('showDepartment').textContent = department;

        }


        function closeShow() {

            document
                .getElementById('showModal')
                .classList
                .add('hidden');

        }


        function openEdit(id, name, brandId, storageId, ramId) {

            document
                .getElementById('editModal')
                .classList
                .remove('hidden');

            document.getElementById('editName').value = name;

            document.getElementById('editBrand').value = brandId;

            document.getElementById('editStorage').value = storageId;

            document.getElementById('editRam').value = ramId;

            document.getElementById('editForm').action = '/devices/' + id;

        }


        function closeEdit() {

            document
                .getElementById('editModal')
                .classList
                .add('hidden');

        }


        function openDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el dispositivo permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D12421',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').action = '/devices/' + id;
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

</x-app-layout>
