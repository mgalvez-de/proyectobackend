{{--
    Convierte una tabla en DataTable (buscador, orden y paginación).
    Uso: <x-datatable id="rams-table" empty="Aún no hay memorias RAM registradas." />
    La tabla debe usar @foreach (no @forelse): DataTables muestra su propio mensaje vacío.
--}}
@props([
    'id',
    'empty' => 'No hay registros.',
    'orderColumn' => 0,
    'order' => 'asc',
])

{{-- librería y estilos: se cargan una sola vez aunque haya varias tablas --}}
@once
    @push('styles')
        <link href="https://cdn.datatables.net/v/dt/dt-3.0.4/datatables.min.css" rel="stylesheet">
        <style>
            div.dt-container .dt-layout-row:not(.dt-layout-table) {
                margin: 0;
                padding: 0.875rem 1.5rem;
                font-size: 0.875rem;
                color: #64748b;
            }

            div.dt-container .dt-layout-row.dt-layout-table {
                margin: 0;
            }

            div.dt-container .dt-search input,
            div.dt-container .dt-length select {
                border: 1px solid #cbd5e1;
                border-radius: 0.5rem;
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            div.dt-container .dt-length select {
                padding-right: 2rem;
                margin-right: 0.375rem;
            }

            div.dt-container .dt-search input:focus,
            div.dt-container .dt-length select:focus {
                outline: none;
                border-color: #D12421;
                box-shadow: 0 0 0 3px rgb(209 36 33 / 0.15);
            }

            div.dt-container table.dataTable > thead > tr > th,
            div.dt-container table.dataTable > tbody > tr > td {
                padding: 1rem 1.5rem;
                text-align: left;
            }

            div.dt-container table.dataTable > thead > tr > th.text-center,
            div.dt-container table.dataTable > tbody > tr > td.text-center {
                text-align: center;
            }

            div.dt-container table.dataTable > thead > tr > th.text-center div.dt-column-header {
                justify-content: center;
            }

            div.dt-container table.dataTable > thead > tr > th {
                border-bottom: 1px solid #e2e8f0;
            }

            div.dt-container table.dataTable > tbody > tr > td.dt-empty {
                padding: 3.5rem 1.5rem;
                text-align: center;
                color: #64748b;
                font-weight: 500;
            }

            div.dt-container table.dataTable th.dt-type-numeric div.dt-column-header,
            div.dt-container table.dataTable th.dt-type-date div.dt-column-header {
                flex-direction: row;
            }

            div.dt-container .dt-paging .dt-paging-button {
                border-radius: 0.375rem !important;
                padding: 0.375rem 0.75rem !important;
            }

            div.dt-container .dt-paging .dt-paging-button.current,
            div.dt-container .dt-paging .dt-paging-button.current:hover {
                background: #D12421 !important;
                border-color: #D12421 !important;
                color: #fff !important;
            }

            div.dt-container .dt-paging .dt-paging-button:not(.current):not(.disabled):hover {
                background: #fef2f2 !important;
                border-color: #fecaca !important;
                color: #A91C19 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.datatables.net/v/dt/dt-3.0.4/datatables.min.js"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabla = document.getElementById(@json($id));

            if (! tabla) {
                return;
            }

            new DataTable(tabla, {
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[{{ (int) $orderColumn }}, @json($order)]],
                columnDefs: [
                    // la columna de acciones no se ordena ni se busca
                    { targets: tabla.querySelector('th.dt-no-sort') ? 'th.dt-no-sort' : [], orderable: false, searchable: false }
                ],
                language: {
                    search: 'Buscar:',
                    searchPlaceholder: 'Escribe para filtrar...',
                    lengthMenu: '_MENU_ registros por página',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Sin registros',
                    infoFiltered: '(filtrado de _MAX_ en total)',
                    zeroRecords: 'No se encontraron resultados.',
                    emptyTable: @json($empty),
                    paginate: { first: '«', previous: '‹', next: '›', last: '»' }
                }
            });
        });
    </script>
@endpush
