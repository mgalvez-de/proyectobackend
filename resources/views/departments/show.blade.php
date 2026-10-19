<x-app-layout :title="$department->displayName()">

    <x-navbar />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if (auth()->user()->rol === 'admin')
            <x-breadcrumbs :items="['Inicio' => route('dashboard'), $department->displayName() => null]" />
        @endif

        <!-- BANNER DEL DEPARTAMENTO -->

        <div class="relative mt-3 mb-6 h-52 sm:h-56 rounded-xl overflow-hidden shadow-md bg-ucsc">

            @if ($department->imageUrl())
                <img src="{{ $department->imageUrl() }}" alt="{{ $department->name }}"
                    class="absolute inset-0 h-full w-full object-cover">
            @endif

            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-black/10"></div>

            <div class="relative h-full flex flex-col sm:flex-row sm:items-end justify-end sm:justify-between gap-4 p-6">

                <div>
                    <p class="text-xs font-semibold text-red-200 uppercase tracking-widest">
                        Departamento
                    </p>

                    <h1 class="text-3xl sm:text-4xl font-bold text-white drop-shadow">
                        {{ $department->displayName() }}
                    </h1>

                    <span
                        class="mt-2 inline-flex items-center rounded-full bg-white/20 backdrop-blur px-3 py-1 text-xs font-semibold text-white">
                        {{ $devices->count() }} {{ $devices->count() === 1 ? 'dispositivo' : 'dispositivos' }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-3">
                    <x-button :href="route('departments.history', $department->id)" variant="secondary">
                        <x-icon name="clock" class="w-4 h-4" />
                        Ver historial
                    </x-button>

                    <x-button type="button" onclick="openCreate()">
                        <x-icon name="plus" class="w-4 h-4" />
                        Añadir dispositivo
                    </x-button>
                </div>

            </div>

        </div>

        <!-- TABLA -->

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">

            <table id="devices-table" class="w-full min-w-[700px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">

                    <tr>

                        <th class="px-6 py-4">ID</th>

                        <th class="px-6 py-4">Nombre</th>

                        <th class="px-6 py-4">Marca</th>

                        <th class="px-6 py-4">Almacenamiento</th>

                        <th class="px-6 py-4">RAM</th>

                        <th class="px-6 py-4 text-center dt-no-sort">Acciones</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($devices as $device)
                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
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
                                        onclick='openShow({{ $device->id }},
                                        @json($device->name),
                                         @json($device->brand?->name ?? 'Sin marca'),
                                         @json($device->deviceType?->name ?? 'Sin tipo'),
                                         @json(($device->storage?->type ?? 'Sin almacenamiento') . ' - ' . ($device->storage?->capacity ?? '')),
                                         @json(($device->processor?->brand ?? 'Sin procesador') . ' - ' . ($device->processor?->model ?? '')),
                                        @json(($device->ram?->type ?? 'Sin RAM') . ' - ' . ($device->ram?->capacity ?? ''))
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

                                    <button type="button" onclick="openDelete({{ $device->id }})"
                                        class="p-1.5 bg-ucsc text-white hover:bg-ucsc-dark rounded transition-colors"
                                        title="Eliminar dispositivo">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v3m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16" />

                                        </svg>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            <x-datatable id="devices-table" empty="No hay dispositivos registrados en este departamento." />

        </div>

    </div>


    <!-- MODAL AGREGAR -->

    <div id="createModal"
        class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center bg-ucsc text-white px-6 py-4">

                <h2 class="text-lg font-semibold">
                    Añadir dispositivo
                </h2>

                <button type="button" onclick="closeCreate()" title="Cerrar"
                    class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>

            </div>

            <div class="px-6 py-5">

                <form method="POST" action="{{ route('devices.store') }}">

                    @csrf

                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
                        </label>

                        <input type="text" name="name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc"
                            placeholder="Ej: PC-{{ $department->name }}-01">

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Marca
                        </label>

                        <select name="brand_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

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
                            Tipo de dispositivo
                        </label>

                        <select name="device_type_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

                            <option value="">Seleccione un tipo de dispositivo</option>

                            @foreach ($deviceTypes as $deviceType)
                                <option value="{{ $deviceType->id }}">
                                    {{ $deviceType->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Procesador
                        </label>

                        <select name="processor_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

                            <option value="">Seleccione un tipo de procesador</option>

                            @foreach ($processors as $processor)
                                <option value="{{ $processor->id }}">
                                    {{ $processor->brand }} - {{ $processor->model }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Almacenamiento
                        </label>

                        <select name="storage_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

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
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

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

    <div id="showModal"
        class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center bg-ucsc text-white px-6 py-4">

                <h2 class="text-lg font-semibold">
                    Detalles del dispositivo
                </h2>

                <button type="button" onclick="closeShow()" title="Cerrar"
                    class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>

            </div>

            <div class="px-6 py-5">

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
                    <strong>Tipo:</strong>
                    <span id="showDeviceType"></span>
                </p>

                <p>
                    <strong>Almacenamiento:</strong>
                    <span id="showStorage"></span>
                </p>

                <p>
                    <strong>Procesador:</strong>
                    <span id="showProcessor"></span>
                </p>

                <p>
                    <strong>RAM:</strong>
                    <span id="showRam"></span>
                </p>


                <div class="mt-6 border-t border-gray-200 pt-5">
                    <h3 class="text-sm font-semibold text-gray-700 text-center mb-3">
                        Código QR del dispositivo
                    </h3>

                    <div class="flex justify-center">
                        <div id="deviceQRCode" class="bg-white p-3 rounded-lg border border-gray-200">
                        </div>
                    </div>

                    <p id="qrDeviceCode" class="text-center text-sm text-gray-500 mt-2">
                    </p>
                </div>


                <a id="createTicketButton" href="#"
                    class="mt-4 inline-block w-full text-center bg-ucsc text-white font-medium py-2.5 px-4 rounded-md hover:bg-ucsc-dark transition-colors">
                    Crear ticket para este dispositivo
                </a>



            </div>

        </div>

    </div>


    <!-- MODAL EDITAR -->

    <div id="editModal"
        class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden max-h-[90vh] overflow-y-auto">

            <div class="flex justify-between items-center bg-ucsc text-white px-6 py-4">

                <h2 class="text-lg font-semibold">
                    Editar dispositivo
                </h2>

                <button type="button" onclick="closeEdit()" title="Cerrar"
                    class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>

            </div>

            <div class="px-6 py-5">

                <form id="editForm" method="POST">

                    @csrf
                    @method('PUT')


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
                        </label>

                        <input type="text" id="editName" name="name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

                    </div>


                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Marca
                        </label>

                        <select id="editBrand" name="brand_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">
                                    {{ $brand->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo de dispositivo
                        </label>

                        <select id="editDeviceType" name="device_type_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">
                            @foreach ($deviceTypes as $deviceType)
                                <option value="{{ $deviceType->id }}">
                                    {{ $deviceType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Procesador
                        </label>

                        <select id="editProcessor" name="processor_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">
                            @foreach ($processors as $processor)
                                <option value="{{ $processor->id }}">
                                    {{ $processor->brand }} - {{ $processor->model }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="mb-4">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Almacenamiento
                        </label>

                        <select id="editStorage" name="storage_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

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
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-ucsc focus:ring-ucsc">

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

        function openShow(id, name, brand, deviceType, storage, processor, ram) {
            document.getElementById('showModal').classList.remove('hidden');

            document.getElementById('createTicketButton').href =
                '/tickets?device_id=' + id;

            document.getElementById('showId').textContent = id;
            document.getElementById('showName').textContent = name;
            document.getElementById('showBrand').textContent = brand;
            document.getElementById('showDeviceType').textContent = deviceType;
            document.getElementById('showStorage').textContent = storage;
            document.getElementById('showProcessor').textContent = processor;
            document.getElementById('showRam').textContent = ram;

            // generar el enlace al formulario del dispositivo
            const qrContainer = document.getElementById('deviceQRCode');


            // reemplaza esta ip por la dirección ipv4 de tu pc
            const baseUrl = 'http://192.168.1.22:8000';
            const ticketUrl = baseUrl + '/tickets/create?device_id=' + id;

            qrContainer.innerHTML = '';


            new QRCode(qrContainer, {
                text: ticketUrl,
                width: 160,
                height: 160,
                colorDark: '#111827',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
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
    </script>

</x-app-layout>
