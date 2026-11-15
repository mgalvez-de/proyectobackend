<x-app-layout title="Mis reportes">


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
            'completed' => ['label' => 'Completada', 'color' => 'green'],
            'cancelled' => ['label' => 'Cancelada', 'color' => 'gray'],
        ];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header title="Mis reportes"
            subtitle="Aquí aparecen todas las fallas que has reportado y en qué estado está cada una."
            :count="$tickets->count()" unit="ticket" />

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <x-stat-card label="Pendientes" :value="$pendingCount" icon="clock" color="yellow" />
            <x-stat-card label="En proceso" :value="$inProgressCount" icon="wrench" color="blue" />
            <x-stat-card label="Cerrados" :value="$closedCount" icon="check-badge" color="green" />
        </div>

        <div class="mb-6 flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 text-slate-600 shadow-sm">
            <x-icon name="ticket" class="w-5 h-5 shrink-0 mt-0.5 text-ucsc" />
            <p class="text-sm">
                Para reportar una nueva falla escanea el <strong>código QR</strong> del equipo con tu celular.
                Cuando el técnico cierre tu ticket, aquí verás la solución que aplicó.
            </p>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">
            <table id="my-reports-table" class="w-full min-w-[1000px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Dispositivo</th>
                        <th class="px-5 py-4">Falla</th>
                        <th class="px-5 py-4">Gravedad</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4">Técnico</th>
                        <th class="px-5 py-4">Solución</th>
                        <th class="px-5 py-4">Fecha</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($tickets as $ticket)
                        @php
                            $severity = $severities[$ticket->severity] ?? null;
                            $status = $statuses[$ticket->status] ?? ['label' => $ticket->status, 'color' => 'gray'];
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-mono text-xs text-slate-500" data-order="{{ $ticket->id }}">
                                #{{ $ticket->id }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="block font-medium text-gray-800">{{ $ticket->device?->name ?? 'Sin dispositivo' }}</span>
                                <span class="block text-xs text-slate-400">{{ $ticket->device?->department?->displayName() ?? '—' }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="block text-xs text-slate-400">{{ ucfirst($ticket->problem_type ?? 'Mantención') }}</span>
                                {{ $ticket->error_description }}
                            </td>

                            <td class="px-5 py-4">
                                @if ($severity)
                                    <x-badge :color="$severity['color']">{{ $severity['label'] }}</x-badge>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <x-badge :color="$status['color']" dot>{{ $status['label'] }}</x-badge>

                                @if ($ticket->closed_at)
                                    <span class="block mt-1.5 text-xs text-slate-400">
                                        {{ $ticket->closed_at->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                {{ $ticket->technician?->name ?? 'Sin asignar' }}
                            </td>

                            <td class="px-5 py-4 min-w-[14rem] max-w-xs">
                                @if ($ticket->solution)
                                    {{ $ticket->solution }}
                                @else
                                    <span class="text-slate-400">Todavía sin solución</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap" data-order="{{ $ticket->created_at->timestamp }}">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <x-datatable id="my-reports-table" empty="Todavía no has reportado ninguna falla." order="desc" />
        </div>

    </div>

</x-app-layout>
