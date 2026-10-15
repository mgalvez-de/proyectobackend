<x-app-layout>

    <x-navbar />

    @php
        $roleNames = [
            'admin' => 'Administrador',
            'supervisor' => 'Supervisor',
            'estudiante' => 'Estudiante',
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
            Volver al dashboard
        </a>

        <!-- encabezado -->

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mt-3 mb-6">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">Usuarios</h1>
                <p class="text-gray-500 mt-1">Administración de las cuentas de usuario del sistema.</p>
            </div>

            <x-button onclick="openCreate()">
                + Nuevo usuario
            </x-button>

        </div>

        <!-- tabla -->

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm text-slate-600 bg-white rounded shadow-sm border border-slate-200">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">RUT</th>
                        <th class="px-6 py-4">Correo</th>
                        <th class="px-6 py-4">Rol</th>
                        <th class="px-6 py-4">Departamento</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($users as $user)

                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $user->id }}</td>

                            <td class="px-6 py-4 font-bold text-slate-800">{{ $user->name }}</td>

                            <td class="px-6 py-4">{{ $user->rut }}</td>

                            <td class="px-6 py-4">{{ $user->email }}</td>

                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $roleNames[$user->rol] ?? $user->rol }}
                                </span>
                            </td>

                            <td class="px-6 py-4">{{ $user->department?->name ?? '—' }}</td>

                            <td class="px-6 py-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <!-- Ver -->

                                    <button type="button" onclick='openShow(
                                            {{ $user->id }},
                                            @json($user->name),
                                            @json($user->rut),
                                            @json($user->email),
                                            @json($roleNames[$user->rol] ?? $user->rol),
                                            @json($user->department?->name ?? "—")
                                        )'
                                        class="p-1.5 bg-gray-500 text-white hover:bg-gray-600 rounded transition-colors"
                                        title="Ver usuario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <!-- Editar -->

                                    <button type="button" onclick='openEdit(
                                            {{ $user->id }},
                                            @json($user->name),
                                            @json($user->rut),
                                            @json($user->email),
                                            @json($user->rol),
                                            @json($user->department_id)
                                        )'
                                        class="p-1.5 bg-yellow-400 text-gray-900 hover:bg-yellow-500 rounded transition-colors"
                                        title="Editar usuario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar -->

                                    <x-button variant="danger" size="sm" type="button" onclick="openDelete({{ $user->id }})"
                                        title="Eliminar usuario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v3m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16" />
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

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">

                <div class="bg-red-700 px-6 py-5 flex items-start justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white">Nuevo usuario</h2>
                        <p class="text-red-100 text-sm mt-1">Crea una cuenta y asígnale un rol.</p>
                    </div>
                    <x-button variant="secondary" size="sm" type="button" onclick="closeCreate()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-button>
                </div>

                <form action="{{ route('users.store') }}" method="POST">

                    @csrf

                    <div class="px-6 py-6 space-y-4">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                            <input type="text" name="name" placeholder="Ej: Juan Pérez" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">RUT (sin puntos ni guion)</label>
                            <input type="text" name="rut" placeholder="Ej: 222208481" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Correo</label>
                            <input type="email" name="email" placeholder="Ej: jperez@ucsc.cl" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Rol</label>
                            <select name="rol" id="createRol" required
                                onchange="toggleDepartment('createRol', 'createDepartmentWrapper', 'createDepartment')"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                                <option value="estudiante">Estudiante</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div id="createDepartmentWrapper" class="hidden">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Departamento</label>
                            <select name="department_id" id="createDepartment"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                                <option value="">Seleccione un departamento</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Contraseña</label>
                            <input type="password" name="password" placeholder="Mínimo 8 caracteres" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Repetir contraseña</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">
                        <x-button variant="secondary" size="sm" type="button" onclick="closeCreate()">
                            Cancelar
                        </x-button>
                        <x-button size="sm" type="submit">
                            Guardar usuario
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
                    <h2 class="text-lg font-semibold">Detalles del usuario</h2>
                    <x-button variant="secondary" size="sm" type="button" onclick="closeShow()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-button>
                </div>

                <div class="p-4 space-y-1">
                    <p><strong>ID:</strong> <span id="showId"></span></p>
                    <p><strong>Nombre:</strong> <span id="showName"></span></p>
                    <p><strong>RUT:</strong> <span id="showRut"></span></p>
                    <p><strong>Correo:</strong> <span id="showEmail"></span></p>
                    <p><strong>Rol:</strong> <span id="showRol"></span></p>
                    <p><strong>Departamento:</strong> <span id="showDepartment"></span></p>
                </div>

            </div>

        </div>


        <!-- Modal editar -->

        <div id="editModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">

                <div class="bg-red-700 px-6 py-5 flex items-start justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white">Editar usuario</h2>
                        <p class="text-red-100 text-sm mt-1">Actualiza los datos, el rol o la contraseña.</p>
                    </div>
                    <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()"
                        class="bg-transparent hover:bg-transparent text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-button>
                </div>

                <form id="editForm" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="px-6 py-6 space-y-4">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                            <input type="text" name="name" id="editName" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">RUT (sin puntos ni guion)</label>
                            <input type="text" name="rut" id="editRut" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Correo</label>
                            <input type="email" name="email" id="editEmail" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Rol</label>
                            <select name="rol" id="editRol" required
                                onchange="toggleDepartment('editRol', 'editDepartmentWrapper', 'editDepartment')"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                                <option value="estudiante">Estudiante</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div id="editDepartmentWrapper" class="hidden">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Departamento</label>
                            <select name="department_id" id="editDepartment"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                                <option value="">Seleccione un departamento</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nueva contraseña</label>
                            <input type="password" name="password" id="editPassword" placeholder="Déjala vacía para no cambiarla"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Repetir nueva contraseña</label>
                            <input type="password" name="password_confirmation" id="editPasswordConfirmation"
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition">
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">
                        <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()">
                            Cancelar
                        </x-button>
                        <x-button size="sm" type="submit">
                            Editar usuario
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

            function toggleDepartment(rolSelectId, wrapperId, departmentSelectId) {
                const isSupervisor = document.getElementById(rolSelectId).value === 'supervisor';

                document.getElementById(wrapperId).classList.toggle('hidden', !isSupervisor);
                document.getElementById(departmentSelectId).required = isSupervisor;
            }

            function openCreate() {
                document.getElementById('createModal').classList.remove('hidden');
                toggleDepartment('createRol', 'createDepartmentWrapper', 'createDepartment');
            }

            function closeCreate() {
                document.getElementById('createModal').classList.add('hidden');
            }

            function openShow(id, name, rut, email, rol, department) {
                document.getElementById('showModal').classList.remove('hidden');

                document.getElementById('showId').textContent = id;
                document.getElementById('showName').textContent = name;
                document.getElementById('showRut').textContent = rut;
                document.getElementById('showEmail').textContent = email;
                document.getElementById('showRol').textContent = rol;
                document.getElementById('showDepartment').textContent = department;
            }

            function closeShow() {
                document.getElementById('showModal').classList.add('hidden');
            }

            function openEdit(id, name, rut, email, rol, departmentId) {
                document.getElementById('editModal').classList.remove('hidden');

                document.getElementById('editName').value = name;
                document.getElementById('editRut').value = rut;
                document.getElementById('editEmail').value = email;
                document.getElementById('editRol').value = rol;
                document.getElementById('editDepartment').value = departmentId ?? '';
                document.getElementById('editPassword').value = '';
                document.getElementById('editPasswordConfirmation').value = '';

                toggleDepartment('editRol', 'editDepartmentWrapper', 'editDepartment');

                document.getElementById('editForm').action = '/users/' + id;
            }

            function closeEdit() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function openDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el usuario permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D12421',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').action = '/users/' + id;
                        document.getElementById('deleteForm').submit();
                    }
                });
            }

            @if (session('success'))
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: @json(session('success')),
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            @endif

            @if (session('error'))
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'No se pudo completar',
                        text: @json(session('error'))
                    });
                });
            @endif

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
