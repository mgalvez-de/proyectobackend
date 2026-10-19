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
        ];

        $breadcrumbs = auth()->user()->rol === 'admin'
            ? ['Inicio' => route('dashboard')]
            : [];
        $breadcrumbs[$department->displayName()] = route('departments.show', $department);
        $breadcrumbs['Historial'] = null;
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <x-page-header title="Historial de {{ $department->displayName() }}"
            subtitle="Tickets reportados para los dispositivos de este departamento." :count="$tickets->count()"
            unit="ticket" :breadcrumbs="$breadcrumbs">
            <x-button :href="route('departments.show', $department)" variant="secondary">
                Volver al departamento
            </x-button>
        </x-page-header>

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-slate-200">
            <table class="w-full min-w-[800px] text-left text-sm text-slate-600">

                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Dispositivo</th>
                        <th class="px-5 py-4">Tipo</th>
                        <th class="px-5 py-4">Falla</th>
                        <th class="px-5 py-4">Gravedad</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4">Fecha</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($tickets as $ticket)
                        @php
                            $severity = $severities[$ticket->severity] ?? ['label' => 'Sin definir', 'color' => 'gray'];
                            $status = $statuses[$ticket->status] ?? ['label' => $ticket->status, 'color' => 'gray'];
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-4 font-mono text-xs text-slate-500">
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
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <x-empty-row colspan="7" message="Este departamento todavía no tiene tickets registrados."
                            hint="Los tickets se crean desde el detalle de cada dispositivo." />
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>
