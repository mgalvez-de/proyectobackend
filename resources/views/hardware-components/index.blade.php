<x-app-layout title="Componentes">

    @php
        $typeColors = [
            'motherboard' => 'blue',
            'screen' => 'green',
            'case' => 'gray',
            'power_supply' => 'orange',
        ];

        $expirationBadges = [
            'expired' => ['label' => 'Vencido', 'color' => 'red'],
            'expiring' => ['label' => 'Vence pronto', 'color' => 'yellow'],
            'ok' => ['label' => 'Vigente', 'color' => 'green'],
        ];

        $inputClass = 'w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header title="Componentes" subtitle="Piezas de cada dispositivo: placa madre, pantalla, gabinete y fuente de poder." :count="$components->count()" unit="componente"
            :breadcrumbs="['Inicio' => route('dashboard'), 'Componentes' => null]">
            <x-button onclick="openCreate()">
                <x-icon name="plus" class="w-4 h-4" />
                Nuevo componente
            </x-button>
        </x-page-header>


        <!-- tabla -->

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">

            <table id="components-table" class="w-full min-w-[1100px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Dispositivo</th>
                        <th class="px-5 py-4">Tipo</th>
                        <th class="px-5 py-4">Marca</th>
                        <th class="px-5 py-4">Modelo</th>
                        <th class="px-5 py-4">N° de serie</th>
                        <th class="px-5 py-4">Instalación</th>
                        <th class="px-5 py-4">Vencimiento</th>
                        <th class="px-5 py-4 text-center dt-no-sort">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($components as $piece)
                        @php
                            $expiration = $expirationBadges[$piece->expirationStatus()] ?? null;
                            $expiresText = $piece->expires_at
                                ? $piece->expires_at->format('d/m/Y') . ' - ' . $expiration['label']
                                : '—';
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $piece->id }}</td>

                            <td class="px-5 py-4">
                                <span class="block font-bold text-slate-800">{{ $piece->device?->name ?? '—' }}</span>
                                <span class="block text-xs text-slate-400">{{ $piece->device?->department?->displayName() }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <x-badge :color="$typeColors[$piece->type] ?? 'gray'">{{ $piece->typeName() }}</x-badge>
                            </td>

                            <td class="px-5 py-4">{{ $piece->brand?->name ?? '—' }}</td>

                            <td class="px-5 py-4">{{ $piece->model ?? '—' }}</td>

                            <td class="px-5 py-4 font-mono text-xs">{{ $piece->serial_number ?? '—' }}</td>

                            <td class="px-5 py-4 whitespace-nowrap" data-order="{{ $piece->installed_at?->timestamp ?? 0 }}">
                                {{ $piece->installed_at?->format('d/m/Y') ?? '—' }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap" data-order="{{ $piece->expires_at?->timestamp ?? 0 }}">
                                @if ($piece->expires_at)
                                    <span class="block">{{ $piece->expires_at->format('d/m/Y') }}</span>
                                    <x-badge :color="$expiration['color']" dot class="mt-1">{{ $expiration['label'] }}</x-badge>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <!-- Ver -->

                                    <button type="button" onclick='openShow(
                                            {{ $piece->id }},
                                            @json($piece->device?->name ?? "—"),
                                            @json($piece->device?->department?->displayName() ?? "—"),
                                            @json($piece->typeName()),
                                            @json($piece->brand?->name ?? "—"),
                                            @json($piece->model ?? "—"),
                                            @json($piece->serial_number ?? "—"),
                                            @json($piece->installed_at?->format("d/m/Y") ?? "—"),
                                            @json($expiresText)
                                        )'
                                        class="p-1.5 bg-gray-500 text-white hover:bg-gray-600 rounded transition-colors"
                                        title="Ver componente">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <!-- Editar -->

                                    <button type="button" onclick='openEdit(
                                            {{ $piece->id }},
                                            @json($piece->device_id),
                                            @json($piece->type),
                                            @json($piece->brand_id),
                                            @json($piece->model),
                                            @json($piece->serial_number),
                                            @json($piece->installed_at?->format("Y-m-d")),
                                            @json($piece->expires_at?->format("Y-m-d"))
                                        )'
                                        class="p-1.5 bg-yellow-400 text-gray-900 hover:bg-yellow-500 rounded transition-colors"
                                        title="Editar componente">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->

                                    <button type="button" onclick="openDelete({{ $piece->id }})"
                                        class="p-1.5 bg-ucsc text-white hover:bg-ucsc-dark rounded transition-colors"
                                        title="Eliminar componente">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v3m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            <x-datatable id="components-table" empty="Aún no hay componentes registrados." />

        </div>


        <!-- Modal crear y editar (mismos campos) -->

        @foreach (['create' => 'Nuevo componente', 'edit' => 'Editar componente'] as $mode => $modalTitle)
            <div id="{{ $mode }}Modal"
                class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[90vh] overflow-y-auto">

                    <div class="bg-ucsc px-6 py-5 flex items-start justify-between">

                        <div>
                            <h2 class="text-xl font-bold text-white">{{ $modalTitle }}</h2>
                            <p class="text-red-100 text-sm mt-1">
                                {{ $mode === 'create' ? 'Registra una pieza de un dispositivo.' : 'Actualiza los datos de la pieza.' }}
                            </p>
                        </div>

                        <button type="button" onclick="closeModal('{{ $mode }}')" title="Cerrar"
                            class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                    </div>

                    <form id="{{ $mode }}Form" method="POST" action="{{ $mode === 'create' ? route('hardware-components.store') : '' }}">

                        @csrf
                        @if ($mode === 'edit')
                            @method('PUT')
                        @endif

                        <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Dispositivo</label>
                                <select name="device_id" id="{{ $mode }}Device" required class="{{ $inputClass }}">
                                    <option value="">Selecciona un dispositivo</option>
                                    @foreach ($departments as $department)
                                        <optgroup label="{{ $department->displayName() }}">
                                            @foreach ($department->devices as $device)
                                                <option value="{{ $device->id }}" @selected($mode === 'create' && (string) old('device_id') === (string) $device->id)>{{ $device->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tipo</label>
                                <select name="type" id="{{ $mode }}Type" required class="{{ $inputClass }}">
                                    <option value="">Selecciona el tipo</option>
                                    @foreach ($types as $key => $label)
                                        <option value="{{ $key }}" @selected($mode === 'create' && old('type') === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Marca <span class="font-normal text-slate-400">(opcional)</span></label>
                                <select name="brand_id" id="{{ $mode }}Brand" class="{{ $inputClass }}">
                                    <option value="">Sin marca</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" @selected($mode === 'create' && (string) old('brand_id') === (string) $brand->id)>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Modelo <span class="font-normal text-slate-400">(opcional)</span></label>
                                <input type="text" name="model" id="{{ $mode }}Model" value="{{ $mode === 'create' ? old('model') : '' }}" placeholder="Ej: B550M" class="{{ $inputClass }}">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">N° de serie <span class="font-normal text-slate-400">(opcional)</span></label>
                                <input type="text" name="serial_number" id="{{ $mode }}Serial" value="{{ $mode === 'create' ? old('serial_number') : '' }}" placeholder="Ej: SN-123456" class="{{ $inputClass }}">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha de instalación</label>
                                <input type="date" name="installed_at" id="{{ $mode }}Installed" value="{{ $mode === 'create' ? old('installed_at') : '' }}" class="{{ $inputClass }}">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha de vencimiento</label>
                                <input type="date" name="expires_at" id="{{ $mode }}Expires" value="{{ $mode === 'create' ? old('expires_at') : '' }}" class="{{ $inputClass }}">
                                <p class="mt-1 text-xs text-slate-400">Garantía o vida útil. Sirve para programar mantenciones preventivas.</p>
                            </div>

                        </div>

                        <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                            <x-button variant="secondary" size="sm" type="button" onclick="closeModal('{{ $mode }}')">
                                Cancelar
                            </x-button>

                            <x-button size="sm" type="submit">
                                {{ $mode === 'create' ? 'Guardar componente' : 'Editar componente' }}
                            </x-button>

                        </div>

                    </form>

                </div>

            </div>
        @endforeach


        <!-- Modal ver -->

        <div id="showModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

                <div class="flex justify-between items-center bg-ucsc text-white px-6 py-4">

                    <h2 class="text-lg font-semibold">Detalles del componente</h2>

                    <button type="button" onclick="closeModal('show')" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>

                <div class="px-6 py-5 space-y-2 text-sm text-slate-700">
                    <p><strong>ID:</strong> <span id="showId"></span></p>
                    <p><strong>Dispositivo:</strong> <span id="showDevice"></span></p>
                    <p><strong>Departamento:</strong> <span id="showDepartment"></span></p>
                    <p><strong>Tipo:</strong> <span id="showType"></span></p>
                    <p><strong>Marca:</strong> <span id="showBrand"></span></p>
                    <p><strong>Modelo:</strong> <span id="showModel"></span></p>
                    <p><strong>N° de serie:</strong> <span id="showSerial"></span></p>
                    <p><strong>Instalación:</strong> <span id="showInstalled"></span></p>
                    <p><strong>Vencimiento:</strong> <span id="showExpires"></span></p>
                </div>

            </div>

        </div>


        <!-- Formulario eliminar -->

        <form id="deleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>


        <!-- JavaScript -->

        <script>

            function openCreate() {
                document.getElementById('createModal').classList.remove('hidden');
            }

            function closeModal(mode) {
                document.getElementById(mode + 'Modal').classList.add('hidden');
            }

            function openShow(id, device, department, type, brand, model, serial, installed, expires) {
                document.getElementById('showModal').classList.remove('hidden');

                document.getElementById('showId').textContent = id;
                document.getElementById('showDevice').textContent = device;
                document.getElementById('showDepartment').textContent = department;
                document.getElementById('showType').textContent = type;
                document.getElementById('showBrand').textContent = brand;
                document.getElementById('showModel').textContent = model;
                document.getElementById('showSerial').textContent = serial;
                document.getElementById('showInstalled').textContent = installed;
                document.getElementById('showExpires').textContent = expires;
            }

            function openEdit(id, deviceId, type, brandId, model, serial, installed, expires) {
                document.getElementById('editModal').classList.remove('hidden');

                document.getElementById('editDevice').value = deviceId;
                document.getElementById('editType').value = type;
                document.getElementById('editBrand').value = brandId ?? '';
                document.getElementById('editModel').value = model ?? '';
                document.getElementById('editSerial').value = serial ?? '';
                document.getElementById('editInstalled').value = installed ?? '';
                document.getElementById('editExpires').value = expires ?? '';

                document.getElementById('editForm').action = '/hardware-components/' + id;
            }

            function openDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el componente permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D12421',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').action = '/hardware-components/' + id;
                        document.getElementById('deleteForm').submit();
                    }
                });
            }

            @if ($errors->any())
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Revisa los datos',
                        text: @json($errors->first()),
                        confirmButtonColor: '#D12421'
                    });
                });
            @endif

        </script>

    </div>

</x-app-layout>
