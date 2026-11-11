<x-app-layout title="Mis tickets">


    @php
        $severities = [
            'low' => ['label' => 'Baja', 'color' => 'green'],
            'medium' => ['label' => 'Media', 'color' => 'yellow'],
            'high' => ['label' => 'Alta', 'color' => 'orange'],
            'critical' => ['label' => 'Crítica', 'color' => 'red'],
        ];

        $statuses = [
            'pending' => ['label' => 'Pendiente', 'color' => 'yellow'],
            'in_progress' => ['label' => 'En proceso', 'color' => 'blue'],
            'closed' => ['label' => 'Cerrado', 'color' => 'green'],
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header title="Mis tickets" subtitle="Fallas que tienes asignadas para reparar." :count="$tickets->count()" unit="ticket" />

        @unless ($technician)
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-yellow-800">
                <x-icon name="exclamation" class="w-5 h-5 shrink-0 mt-0.5" />
                <p class="text-sm">
                    Tu cuenta todavía no está asociada a una ficha de técnico. Pide al administrador que la vincule en
                    <strong>Agregar opciones → Técnicos</strong>.
                </p>
            </div>
        @endunless

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">

            <table id="my-tickets-table" class="w-full min-w-[900px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Dispositivo</th>
                        <th class="px-5 py-4">Departamento</th>
                        <th class="px-5 py-4">Falla</th>
                        <th class="px-5 py-4">Gravedad</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4">Fecha</th>
                        <th class="px-5 py-4 text-center dt-no-sort">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($tickets as $ticket)
                        @php
                            $severity = $severities[$ticket->severity] ?? ['label' => 'Sin definir', 'color' => 'gray'];
                            $status = $statuses[$ticket->status] ?? ['label' => $ticket->status, 'color' => 'gray'];
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-mono text-xs text-slate-500" data-order="{{ $ticket->id }}">#{{ $ticket->id }}</td>

                            <td class="px-5 py-4 font-medium text-gray-800">{{ $ticket->device?->name ?? 'Sin dispositivo' }}</td>

                            <td class="px-5 py-4">{{ $ticket->device?->department?->displayName() ?? '—' }}</td>

                            <td class="px-5 py-4">
                                <span class="block text-xs text-slate-400">{{ ucfirst($ticket->problem_type) }}</span>
                                {{ $ticket->error_description }}
                            </td>

                            <td class="px-5 py-4">
                                <x-badge :color="$severity['color']">{{ $severity['label'] }}</x-badge>
                            </td>

                            <td class="px-5 py-4">
                                <x-badge :color="$status['color']" dot>{{ $status['label'] }}</x-badge>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap" data-order="{{ $ticket->created_at->timestamp }}">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <button type="button" onclick='openAttend(
                                        {{ $ticket->id }},
                                        @json($ticket->device?->name ?? "Sin dispositivo"),
                                        @json($ticket->error_description),
                                        @json($ticket->comments ?? ""),
                                        @json($ticket->status),
                                        @json($ticket->solution ?? "")
                                    )'
                                    class="inline-flex items-center gap-1.5 rounded-md bg-ucsc px-3 py-1.5 text-xs font-semibold text-white hover:bg-ucsc-dark transition-colors"
                                    title="Atender ticket">
                                    <x-icon name="wrench" class="w-4 h-4" />
                                    Atender
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <x-datatable id="my-tickets-table" empty="No tienes tickets asignados por ahora." order="desc" />

        </div>


        <!-- Modal atender -->

        <div id="attendModal"
            class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">

                <div class="bg-ucsc px-6 py-5 flex items-start justify-between">

                    <div>
                        <h2 class="text-xl font-bold text-white">Atender ticket <span id="attendId"></span></h2>
                        <p class="text-red-100 text-sm mt-1">Actualiza el estado y registra la solución aplicada.</p>
                    </div>

                    <button type="button" onclick="closeAttend()" title="Cerrar"
                        class="rounded-md p-1 text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>

                <form id="attendForm" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="px-6 py-6 space-y-4">

                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-4 text-sm text-slate-700 space-y-1">
                            <p><strong>Dispositivo:</strong> <span id="attendDevice"></span></p>
                            <p><strong>Falla:</strong> <span id="attendFailure"></span></p>
                            <p><strong>Comentarios:</strong> <span id="attendComments"></span></p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                            <select name="status" id="attendStatus" required
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition">
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En proceso</option>
                                <option value="closed">Cerrado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Solución aplicada <span class="font-normal text-slate-400">(obligatoria para cerrar)</span>
                            </label>
                            <textarea name="solution" id="attendSolution" rows="4"
                                placeholder="Describe qué hiciste para solucionar la falla..."
                                class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ucsc/20 focus:border-ucsc transition"></textarea>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">

                        <x-button variant="secondary" size="sm" type="button" onclick="closeAttend()">
                            Cancelar
                        </x-button>

                        <x-button size="sm" type="submit">
                            Guardar cambios
                        </x-button>

                    </div>

                </form>

            </div>

        </div>


        <script>

            function openAttend(id, device, failure, comments, status, solution) {
                document.getElementById('attendModal').classList.remove('hidden');

                document.getElementById('attendId').textContent = '#' + id;
                document.getElementById('attendDevice').textContent = device;
                document.getElementById('attendFailure').textContent = failure;
                document.getElementById('attendComments').textContent = comments || '—';
                document.getElementById('attendStatus').value = status;
                document.getElementById('attendSolution').value = solution;

                document.getElementById('attendForm').action = '/mis-tickets/' + id;
            }

            function closeAttend() {
                document.getElementById('attendModal').classList.add('hidden');
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
