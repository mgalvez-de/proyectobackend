<x-app-layout title="Técnicos">


    @php
        $specialtyColors = [
            'hardware' => 'orange',
            'software' => 'blue',
            'redes' => 'green',
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header title="Técnicos" subtitle="Personas encargadas de reparar los dispositivos." :count="$technicians->count()" unit="técnico"
            :breadcrumbs="['Inicio' => route('dashboard'), 'Técnicos' => null]">
            <x-button onclick="openCreate()">
                <x-icon name="plus" class="w-4 h-4" />
                Nuevo técnico
            </x-button>
        </x-page-header>


        <!-- tabla -->

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">

            <table id="technicians-table" class="w-full min-w-[1000px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Correo</th>
                        <th class="px-6 py-4">Teléfono</th>
                        <th class="px-6 py-4">Especialidad</th>
                        <th class="px-6 py-4">Departamento</th>
                        <th class="px-6 py-4">Cuenta</th>
                        <th class="px-6 py-4 text-center dt-no-sort">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($technicians as $technician)

                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $technician->id }}</td>

                            <td class="px-6 py-4 font-bold text-slate-800">{{ $technician->name }}</td>

                            <td class="px-6 py-4">{{ $technician->email }}</td>

                            <td class="px-6 py-4">{{ $technician->phone ?? '—' }}</td>

                            <td class="px-6 py-4">
                                <x-badge :color="$specialtyColors[$technician->specialty] ?? 'gray'">
                                    {{ $technician->specialtyName() }}
                                </x-badge>
                            </td>

                            <td class="px-6 py-4">{{ $technician->department?->name ?? 'Todos' }}</td>

                            <td class="px-6 py-4">
                                @if ($technician->user)
                                    <span class="font-mono text-xs">{{ $technician->user->rut }}</span>
                                @else
                                    <span class="text-slate-400">Sin cuenta</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <!-- Ver -->

                                    <button type="button" onclick='openShow(
                                            {{ $technician->id }},
                                            @json($technician->name),
                                            @json($technician->email),
                                            @json($technician->phone ?? "—"),
                                            @json($technician->specialtyName()),
                                            @json($technician->department?->name ?? "Todos"),
                                            @json($technician->user ? $technician->user->name . " (RUT " . $technician->user->rut . ")" : "Sin cuenta")
                                        )'
                                        class="p-1.5 bg-gray-500 text-white hover:bg-gray-600 rounded transition-colors"
                                        title="Ver técnico">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <!-- Editar -->

                                    <button type="button" onclick='openEdit(
                                            {{ $technician->id }},
                                            @json($technician->name),
                                            @json($technician->email),
                                            @json($technician->phone),
                                            @json($technician->specialty),
                                            @json($technician->department_id),
                                            @json($technician->user_id)
                                        )'
                                        class="p-1.5 bg-yellow-400 text-gray-900 hover:bg-yellow-500 rounded transition-colors"
                                        title="Editar técnico">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->

                                    <button type="button" onclick="openDelete({{ $technician->id }})"
                                        class="p-1.5 bg-ucsc text-white hover:bg-ucsc-dark rounded transition-colors"
                                        title="Eliminar técnico">
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

            <x-datatable id="technicians-table" empty="Aún no hay técnicos registrados." />

        </div>


        <!-- Modal crear -->

        <div id="createModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">

                <div class="bg-ucsc px-6 py-5 flex items-start justify-between">

                    <div>
                        <h2 class="text-xl font-bold text-white">Nuevo técnico</h2>
                        <p class="text-red-100 text-sm mt-1">Registra a una persona encargada de reparar dispositivos.</p>
                    </div>

                    <button type="button" onclick="closeCreate()" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>

                <form action="{{ route('technicians.store') }}" method="POST">

                    @csrf

                    <div class="px-6 py-6 space-y-4">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Juan Pérez" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Correo</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Ej: jperez@ucsc.cl" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono <span class="font-normal text-slate-400">(opcional)</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Ej: +56 9 1234 5678"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Especialidad</label>
                            <select name="specialty" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                                <option value="">Selecciona una especialidad</option>
                                @foreach ($specialties as $key => $label)
                                    <option value="{{ $key }}" @selected(old('specialty') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Departamento</label>
                            <select name="department_id"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                                <option value="">Todos los departamentos</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected((string) old('department_id') === (string) $department->id)>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Cuenta para iniciar sesión <span class="font-normal text-slate-400">(opcional)</span></label>
                            <select name="user_id"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                                <option value="">Sin cuenta</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}" @selected((string) old('user_id') === (string) $account->id)>{{ $account->name }} (RUT {{ $account->rut }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-400">Se crean en Usuarios con el rol "Técnico".</p>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeCreate()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Guardar técnico
                        </x-button>

                    </div>

                </form>

            </div>

        </div>


        <!-- Modal ver -->

        <div id="showModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

                <div class="flex justify-between items-center bg-ucsc text-white px-6 py-4">

                    <h2 class="text-lg font-semibold">Detalles del técnico</h2>

                    <button type="button" onclick="closeShow()" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>

                <div class="px-6 py-5 space-y-2 text-sm text-slate-700">
                    <p><strong>ID:</strong> <span id="showId"></span></p>
                    <p><strong>Nombre:</strong> <span id="showName"></span></p>
                    <p><strong>Correo:</strong> <span id="showEmail"></span></p>
                    <p><strong>Teléfono:</strong> <span id="showPhone"></span></p>
                    <p><strong>Especialidad:</strong> <span id="showSpecialty"></span></p>
                    <p><strong>Departamento:</strong> <span id="showDepartment"></span></p>
                    <p><strong>Cuenta:</strong> <span id="showAccount"></span></p>
                </div>

            </div>

        </div>


        <!-- Modal editar -->

        <div id="editModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">

                <div class="bg-ucsc px-6 py-5 flex items-start justify-between">

                    <div>
                        <h2 class="text-xl font-bold text-white">Editar técnico</h2>
                        <p class="text-red-100 text-sm mt-1">Actualiza los datos del técnico.</p>
                    </div>

                    <button type="button" onclick="closeEdit()" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>

                <form id="editForm" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="px-6 py-6 space-y-4">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                            <input type="text" name="name" id="editName" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Correo</label>
                            <input type="email" name="email" id="editEmail" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono <span class="font-normal text-slate-400">(opcional)</span></label>
                            <input type="text" name="phone" id="editPhone"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Especialidad</label>
                            <select name="specialty" id="editSpecialty" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                                @foreach ($specialties as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Departamento</label>
                            <select name="department_id" id="editDepartment"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                                <option value="">Todos los departamentos</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Cuenta para iniciar sesión <span class="font-normal text-slate-400">(opcional)</span></label>
                            <select name="user_id" id="editUser"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                                <option value="">Sin cuenta</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} (RUT {{ $account->rut }})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Editar técnico
                        </x-button>

                    </div>

                </form>

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

            function closeCreate() {
                document.getElementById('createModal').classList.add('hidden');
            }

            function openShow(id, name, email, phone, specialty, department, account) {
                document.getElementById('showModal').classList.remove('hidden');

                document.getElementById('showId').textContent = id;
                document.getElementById('showName').textContent = name;
                document.getElementById('showEmail').textContent = email;
                document.getElementById('showPhone').textContent = phone;
                document.getElementById('showSpecialty').textContent = specialty;
                document.getElementById('showDepartment').textContent = department;
                document.getElementById('showAccount').textContent = account;
            }

            function closeShow() {
                document.getElementById('showModal').classList.add('hidden');
            }

            function openEdit(id, name, email, phone, specialty, departmentId, userId) {
                document.getElementById('editModal').classList.remove('hidden');

                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPhone').value = phone ?? '';
                document.getElementById('editSpecialty').value = specialty;
                document.getElementById('editDepartment').value = departmentId ?? '';
                document.getElementById('editUser').value = userId ?? '';

                document.getElementById('editForm').action = '/technicians/' + id;
            }

            function closeEdit() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function openDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará al técnico permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D12421',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').action = '/technicians/' + id;
                        document.getElementById('deleteForm').submit();
                    }
                });
            }

            @if ($errors->any())
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Revisa los datos',
                        text: @json($errors->first())
                    });
                });
            @endif

        </script>

    </div>

</x-app-layout>
