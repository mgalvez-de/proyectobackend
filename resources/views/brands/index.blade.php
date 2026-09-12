<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">


            <!--encabezado-->

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Marcas
                </h1>

                <p class="text-gray-500 mt-1">
                    Administración de las marcas de dispositivos del sistema.
                </p>
            </div>

            <x-button onclick="openCreate()">
                + Nueva marca
            </x-button>

        </div>


        <!-- tabla -->

        <div class="overflow-x-auto">

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

                    @foreach ($brands as $brand)
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

                                    <button onclick="openShow()"
                                        class="p-1.5 text-slate-600 hover:bg-slate-100 rounded transition-colors"
                                        title="Ver marca">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                    </button>


                                    <!-- boton editar -->

                                    <button onclick='openEdit({{ $brand->id }}, "{{ $brand->name }}")'>

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>

                                    </button>


                                    <!-- boton eliminar -->

                                    <button onclick="openDelete({{ $brand->id }})"
                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                                        title="Eliminar marca">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>

                                    </button>

                                </div>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>


        <!-- Modal para crear una marca -->

        <div id="createModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Encabezado -->

                <div class="bg-blue-900 px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Nueva marca
                        </h2>

                        <p class="text-blue-100 text-sm mt-1">
                            Registra una nueva marca de dispositivos.
                        </p>

                    </div>

                    <button type="button" onclick="closeCreate()"
                        class="text-blue-100 hover:text-white hover:bg-blue-800 rounded-lg p-1.5 transition-colors cursor-pointer">

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
                    focus:outline-none focus:ring-2 focus:ring-blue-100
                    focus:border-blue-500 transition">

                    </div>


                    <!-- botones para modals -->

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <button type="button" onclick="closeCreate()"
                            class="px-4 py-2 text-sm font-medium text-slate-700
                    bg-white border border-slate-300 rounded-lg
                    hover:bg-slate-100 transition-colors cursor-pointer">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white
                    bg-blue-900 rounded-lg hover:bg-blue-800
                    transition-colors cursor-pointer">
                            Guardar marca
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- Modal para ver una marca -->
        <div id="showModal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center bg-blue-900 text-white px-4 py-2 rounded-t-lg">
                    <h2 class="text-lg font-semibold">Detalles de la marca</h2>
                    <button onclick="document.getElementById('showModal').classList.add('hidden')"
                        class="text-white hover:text-gray-300">&times;</button>
                </div>
                <div class="p-4">
                    <p><strong>ID:</strong> 1</p>
                    <p><strong>Nombre:</strong> Lenovo</p>
                </div>
            </div>
        </div>

        <!--Modal para editar una marca -->
        <div
            id="editModal"class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

                <!-- Encabezado -->

                <div class="bg-blue-900 px-6 py-5 flex items-start justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-white">
                            Editar marca
                        </h2>

                        <p class="text-blue-100 text-sm mt-1">
                            Actualizar una marca para dispositivos
                        </p>

                    </div>

                    <button type="button" onclick="closeEdit()"
                        class="text-blue-100 hover:text-white hover:bg-blue-800 rounded-lg p-1.5 transition-colors cursor-pointer">

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
                    focus:outline-none focus:ring-2 focus:ring-blue-100
                    focus:border-blue-500 transition">

                    </div>


                    <!-- Botones -->

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <button type="button" onclick="closeEdit()"
                            class="px-4 py-2 text-sm font-medium text-slate-700
                    bg-white border border-slate-300 rounded-lg
                    hover:bg-slate-100 transition-colors cursor-pointer">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-900 rounded-lg hover:bg-blue-800 transition-colors cursor-pointer">
                            Editar marca
                        </button>

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

            function openShow() {
                document
                    .getElementById('showModal')
                    .classList.remove('hidden');
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
                if (confirm('¿Estás seguro de que deseas eliminar esta marca?')) {
                    document.getElementById('deleteForm').action = '/brands/' + id;
                    document.getElementById('deleteForm').submit();
                }
            }
        </script>
    </div>
</x-app-layout>
