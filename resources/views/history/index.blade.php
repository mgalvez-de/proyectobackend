<x-app-layout :title="'Historial · ' . $department->displayName()">

    <x-navbar />

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

        $rol = auth()->user()->rol;
        $soloPropios = $soloPropios ?? false;
        $puedeCerrar = in_array($rol, ['admin', 'supervisor']);
        $puedeGestionarPreventivas = in_array($rol, ['admin', 'supervisor']);

        if ($soloPropios) {
            $breadcrumbs = [
                'Inicio' => route('dashboard'),
                'Mis reportes' => null,
            ];
        } else {
            $breadcrumbs = $rol === 'admin'
                ? ['Inicio' => route('dashboard')]
                : [];
            $breadcrumbs[$department->displayName()] = route('departments.show', $department);
            $breadcrumbs['Historial'] = null;
        }
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header
            :title="$soloPropios
                ? 'Mis reportes en ' . $department->displayName()
                : 'Historial de ' . $department->displayName()"
            :subtitle="$soloPropios
                ? 'Estos son los tickets que tú has reportado en este departamento.'
                : 'Mantenciones reactivas y preventivas de los dispositivos de este departamento.'"
            :count="$tickets->count()" unit="registro" :breadcrumbs="$breadcrumbs">
            @if ($soloPropios)
                <x-button :href="route('dashboard')" variant="secondary">
                    Volver al inicio
                </x-button>
            @else
                <x-button :href="route('departments.show', $department)" variant="secondary">
                    Volver al departamento
                </x-button>
            @endif
        </x-page-header>

        <div class="grid grid-cols-1 {{ $soloPropios ? '' : 'sm:grid-cols-2' }} gap-4 mb-6">

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 flex items-center gap-4">

                <div class="rounded-lg bg-orange-50 p-3 text-orange-600">
                    <x-icon name="exclamation" class="w-6 h-6" />
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        {{ $soloPropios ? 'Tickets que has reportado' : 'Mantenciones reactivas' }}
                    </p>

                    <p class="text-3xl font-bold text-gray-800">
                        {{ $reactiveCount }}
                    </p>
                </div>

            </div>

            @unless ($soloPropios)
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 flex items-center gap-4">

                    <div class="rounded-lg bg-blue-50 p-3 text-blue-600">
                        <x-icon name="wrench" class="w-6 h-6" />
                    </div>

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Mantenciones preventivas
                        </p>

                        <p class="text-3xl font-bold text-gray-800">
                            {{ $preventiveCount }}
                        </p>
                    </div>

                </div>
            @endunless

        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">
            <table id="history-table" class="w-full min-w-[1100px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Dispositivo</th>
                        <th class="px-5 py-4">Mantención</th>
                        <th class="px-5 py-4">Detalle</th>
                        <th class="px-5 py-4">Responsable</th>
                        <th class="px-5 py-4">Gravedad</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4">Fecha</th>
                        <th class="px-5 py-4 text-center dt-no-sort">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @foreach ($tickets as $ticket)
                        @php
                            $esPreventiva = $ticket->isPreventive();

                            $severity = $severities[$ticket->severity] ?? null;
                            $status = $statuses[$ticket->status] ?? ['label' => $ticket->status, 'color' => 'gray'];

                            $subtipo = $esPreventiva
                                ? ($ticket->maintenanceType?->name ?? 'Sin tipo')
                                : ucfirst($ticket->problem_type);

                            $subtipoIcono = $esPreventiva
                                ? 'clock'
                                : ($ticket->problem_type === 'hardware' ? 'wrench' : 'code');
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-mono text-xs text-slate-500" data-order="{{ $ticket->id }}">
                                #{{ $ticket->id }}
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $ticket->device?->name ?? 'Sin dispositivo' }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-col gap-1.5">
                                    <x-badge :color="$esPreventiva ? 'blue' : 'orange'">
                                        {{ $esPreventiva ? 'Preventiva' : 'Reactiva' }}
                                    </x-badge>

                                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-500">
                                        <x-icon :name="$subtipoIcono" class="w-3.5 h-3.5 text-slate-400" />
                                        {{ $subtipo }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                {{ $ticket->error_description }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $ticket->user?->name ?? 'Sin responsable' }}
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
                                    <span class="block mt-1.5 text-xs text-slate-500">
                                        {{ $esPreventiva ? 'Gestionada' : 'Cerrado' }} por
                                        {{ $ticket->closedBy?->name ?? 'Sin registro' }}
                                    </span>

                                    <span class="block text-xs text-slate-400">
                                        {{ $ticket->closed_at->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap" data-order="{{ $ticket->created_at->timestamp }}">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}

                                @if ($esPreventiva && $ticket->scheduled_at)
                                    <span class="block text-xs text-slate-500">
                                        Programada: {{ $ticket->scheduled_at->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if (!$esPreventiva && $puedeCerrar && $ticket->status !== 'closed')
                                    <form method="POST" action="{{ route('tickets.close', $ticket) }}">
                                        @csrf

                                        <x-button type="submit" variant="success" size="sm">
                                            Cerrar
                                        </x-button>
                                    </form>
                                @elseif ($esPreventiva && $puedeGestionarPreventivas && $ticket->status === 'pending')
                                    <div class="flex justify-center gap-2">
                                        <form method="POST"
                                            action="{{ route('preventive-maintenances.update', $ticket) }}">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="status" value="completed">

                                            <x-button type="submit" variant="success" size="sm">
                                                Completar
                                            </x-button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('preventive-maintenances.update', $ticket) }}">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="status" value="cancelled">

                                            <x-button type="submit" variant="secondary" size="sm">
                                                Cancelar
                                            </x-button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <x-datatable id="history-table" empty="Este departamento todavía no tiene mantenciones registradas."
                order="desc" />
        </div>

    </div>
</x-app-layout>
