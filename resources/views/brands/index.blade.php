<x-app-layout title="Marcas">
    <x-navbar />
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-page-header title="Marcas" subtitle="Administración de las marcas de dispositivos del sistema." :count="$brands->count()" unit="registro"
            :breadcrumbs="['Inicio' => route('dashboard'), 'Marcas' => null]">
            <x-button onclick="openCreate()">
                <x-icon name="plus" class="w-4 h-4" />
                Nueva marca
            </x-button>
        </x-page-header>


        <!-- tabla -->

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">

            <table class="w-full min-w-[600px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>

                        <th class="px-6 py-4">
                            ID
                        </th>

                        <th class="px-6 py-4">
                            Nombre
                        </th>

                        <th class="px-6 py-4 text-center">
                            Acciones
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($brands as $brand)
                        <tr class="hover:bg-slate-50 transition-colors">

                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                {{ $brand->id }}
                            </td>

                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $brand->name }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                <div class="flex justify-center gap-2">

                                    <!-- Ver -->

                                    <button type="button"
                                        onclick='openShow({{ $brand->id }}, "{{ $brand->name }}")'
                                        class="p-1.5 bg-gray-500 text-white hover:bg-gray-600 rounded transition-colors"
                                        title="Ver marca">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                    </button>


                                    <!-- boton editar -->

                                    <button onclick='openEdit({{ $brand->id }}, "{{ $brand->name }}")'
                                        class="p-1.5 bg-yellow-400 text-gray-900 hover:bg-yellow-500 rounded transition-colors"
                                        title="Editar marca">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>


                                    <!-- boton eliminar -->

                                    <button type="button" onclick="openDelete({{ $brand->id }})"
                                        class="p-1.5 bg-ucsc text-white hover:bg-ucsc-dark rounded transition-colors"
                                        title="Eliminar marca">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v3m1-10V4a1 1 0 00-1-1h-4a1 1 0 01-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>

                                </div>

                            </td>

                        </tr>
                    @empty
                        <x-empty-row colspan="3" message="Aún no hay marcas registradas."
                            hint="Usa el botón «Nueva marca» para comenzar." />
                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- Modal para crear una marca -->

        <div id="createModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Encabezado -->

                <div class="bg-ucsc px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Nueva marca
                        </h2>

                        <p class="text-red-100 text-sm mt-1">
                            Registra una nueva marca de dispositivos.
                        </p>

                    </div>

                    <button type="button" onclick="closeCreate()" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>


                <!-- Formulario -->

                <form action="{{ route('brands.store') }}" method="POST">

                    @csrf

                    <div class="px-6 py-6">

                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nombre
                        </label>

                        <input type="text" name="name" id="name" placeholder="Ej: Lenovo"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-ucsc/20
                    focus:border-ucsc transition">

                    </div>


                    <!-- botones para modals -->

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeCreate()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Guardar marca
                        </x-button>

                    </div>

                </form>

            </div>

        </div>

        <!-- Modal para ver una marca -->
        <div id="showModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="flex justify-between items-center bg-ucsc text-white px-6 py-4">
                    <h2 class="text-lg font-semibold">Detalles de la marca</h2>

                    <button type="button" onclick="closeShow()" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
                <div class="px-6 py-5 space-y-2 text-sm text-slate-700">
                    <p><strong>ID:</strong> <span id="showId"></span></p>
                    <p><strong>Nombre:</strong> <span id="showName"></span></p>
                </div>
            </div>
        </div>

        <!--Modal para editar una marca -->
        <div
            id="editModal"class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Encabezado -->

                <div class="bg-ucsc px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Editar marca
                        </h2>

                        <p class="text-red-100 text-sm mt-1">
                            Actualizar una marca para dispositivos
                        </p>

                    </div>

                    <button type="button" onclick="closeEdit()" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>


                <!-- Formulario -->

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-6">

                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nombre
                        </label>

                        <input type="text" name="name" id="editName" placeholder="Ej: Lenovo"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-ucsc/20
                    focus:border-ucsc transition">

                    </div>


                    <!-- Botones -->

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeEdit()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Editar marca
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

            function openShow(id, name) {
                document.getElementById('showModal').classList.remove('hidden');

                document.getElementById('showId').textContent = id;
                document.getElementById('showName').textContent = name;
            }

            function closeShow() {
                document
                    .getElementById('showModal')
                    .classList.add('hidden');
            }

            function openEdit(id, name) {
                document.getElementById('editModal').classList.remove('hidden');

                document.getElementById('editName').value = name;

                document.getElementById('editForm').action = '/brands/' + id;
            }

            function closeEdit() {
                document
                    .getElementById('editModal')
                    .classList.add('hidden');
            }

            function openDelete(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará la marca permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D12421',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').action = '/brands/' + id;
                        document.getElementById('deleteForm').submit();
                    }
                });
            }

        </script>
    </div>
</x-app-layout>
