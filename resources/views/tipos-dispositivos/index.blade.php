x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800">
                Tipos de dispositivos
            </h2>

            <a href="{{ route('tipos-dispositivos.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-md">
                + Crear tipo
            </a>

        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">

                <table id="tipos-dispositivos-table" class="min-w-full border border-gray-200">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-4 py-3 text-left">
                                ID
                            </th>

                            <th class="px-4 py-3 text-left">
                                Tipo
                            </th>

                            <th class="px-4 py-3 text-left">
                                Acciones
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($tipos as $tipo)

                            <tr class="border-t">

                                <td class="px-4 py-3">
                                    {{ $tipo->id }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $tipo->tipo }}
                                </td>

                                <td class="px-4 py-3">

                                    <a href="{{ route('tipos-dispositivos.edit', $tipo) }}"
                                       class="text-blue-600 mr-3">
                                        Editar
                                    </a>

                                    <form action="{{ route('tipos-dispositivos.destroy', $tipo) }}"
                                          method="POST"
                                          class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-red-600"
                                                onclick="return confirm('¿Seguro que deseas eliminar este tipo?')">
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@push('styles')
    <link href="https://cdn.datatables.net/v/dt/dt-3.0.4/datatables.min.css" rel="stylesheet">
    <style>
        #tipos-dispositivos-table_wrapper .dt-length select {
            min-width: 60px;
        }

        #tipos-dispositivos-table_wrapper .dt-search input,
        #tipos-dispositivos-table_wrapper .dt-length select {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.5rem;
        }

        #tipos-dispositivos-table_wrapper .dt-paging button {
            border-radius: 0.375rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/v/dt/dt-3.0.4/datatables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabla = document.getElementById('tipos-dispositivos-table');

            if (tabla) {
                new DataTable(tabla, {
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/3.0.4/i18n/es-ES.js',
                        emptyTable: 'No hay tipos registrados.'
                    },
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50],
                    order: [[0, 'asc']],
                    columnDefs: [
                        {
                            targets: -1,
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }
        });
    </script>
@endpush

</x-app-layout>
