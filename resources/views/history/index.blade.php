<x-app-layout :title="'Historial · ' . $department->displayName()">


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

        $breadcrumbs = auth()->user()->rol === 'admin'
            ? ['Inicio' => route('dashboard')]
            : [];
        $breadcrumbs[$department->displayName()] = route('departments.show', $department);
        $breadcrumbs['Historial'] = null;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header title="Historial de {{ $department->displayName() }}"
            subtitle="Tickets reportados para los dispositivos de este departamento." :count="$tickets->count()"
            unit="ticket" :breadcrumbs="$breadcrumbs">
            <x-button :href="route('departments.show', $department)" variant="secondary">
                Volver al departamento
            </x-button>
        </x-page-header>

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">
            <table id="history-table" class="w-full min-w-[960px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Dispositivo</th>
                        <th class="px-5 py-4">Tipo</th>
                        <th class="px-5 py-4">Falla</th>
                        <th class="px-5 py-4">Gravedad</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4 dt-no-sort">Encargado</th>
                        <th class="px-5 py-4">Fecha</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($tickets as $ticket)
                        @php
                            $severity = $severities[$ticket->severity] ?? ['label' => 'Sin definir', 'color' => 'gray'];
                            $status = $statuses[$ticket->status] ?? ['label' => $ticket->status, 'color' => 'gray'];
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-mono text-xs text-slate-500" data-order="{{ $ticket->id }}">
                                #{{ $ticket->id }}
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $ticket->device?->name ?? 'Sin dispositivo' }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 text-slate-700">
                                    <x-icon :name="$ticket->problem_type === 'hardware' ? 'wrench' : 'code'"
                                        class="w-4 h-4 text-slate-400" />
                                    {{ ucfirst($ticket->problem_type) }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                {{ $ticket->error_description }}
                            </td>

                            <td class="px-5 py-4">
                                <x-badge :color="$severity['color']">{{ $severity['label'] }}</x-badge>
                            </td>

                            <td class="px-5 py-4">
                                <x-badge :color="$status['color']" dot>{{ $status['label'] }}</x-badge>
                                @if ($ticket->solution)
                                    <span class="mt-1 block max-w-[10rem] truncate text-xs text-slate-400" title="{{ $ticket->solution }}">
                                        {{ $ticket->solution }}
                                    </span>
                                @endif
                            </td>

                            <!-- técnico encargado: el admin o el supervisor lo elige de la lista -->
                            <td class="px-5 py-4">
                                @if (in_array(auth()->user()->rol, ['admin', 'supervisor']))
                                    <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="technician_id" onchange="this.form.submit()" title="Asignar técnico"
                                            class="w-36 rounded-md border-slate-300 py-1.5 text-xs focus:border-ucsc focus:ring-ucsc">
                                            <option value="">Sin asignar</option>
                                            @foreach ($technicians as $technician)
                                                <option value="{{ $technician->id }}" @selected((int) $ticket->technician_id === (int) $technician->id)>
                                                    {{ $technician->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    {{ $ticket->technician?->name ?? 'Sin asignar' }}
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap" data-order="{{ $ticket->created_at->timestamp }}">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <x-datatable id="history-table" empty="Este departamento todavía no tiene tickets registrados." order="desc" />
        </div>

    </div>
</x-app-layout>
