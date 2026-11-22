{{-- Historial de un equipo en PDF (dompdf no usa Tailwind: los estilos van aquí mismo) --}}
@php
    $severities = ['low' => 'Baja', 'medium' => 'Media', 'high' => 'Alta', 'critical' => 'Crítica'];
    $statuses = [
        'pending' => 'Pendiente', 'in_progress' => 'En proceso', 'closed' => 'Cerrado',
        'completed' => 'Completada', 'cancelled' => 'Cancelada',
    ];

    // tiempo que tardó la solución, en palabras simples
    $duration = function ($from, $to) {
        if (! $from || ! $to) {
            return '—';
        }
        $hours = $from->diffInHours($to);

        return $hours < 24
            ? max(1, (int) $hours) . ' h'
            : (int) floor($hours / 24) . ' día' . (floor($hours / 24) == 1 ? '' : 's');
    };

    $logo = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/ucsc-logo-horizontal.png')));
    $reactive = $tickets->where('type', 'reactive')->count();
    $preventive = $tickets->where('type', 'preventive')->count();
    $finished = $tickets->whereIn('status', ['closed', 'completed'])->count();
    $open = $tickets->whereIn('status', ['pending', 'in_progress'])->count();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial {{ $device->name }}</title>
    <style>
        @page { margin: 28px 32px 40px 32px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5px; color: #1f2937; }
        .header { border-bottom: 3px solid #D12421; padding-bottom: 8px; margin-bottom: 12px; }
        .header td { vertical-align: middle; }
        h1 { font-size: 17px; margin: 0; color: #D12421; }
        .muted { color: #6b7280; }
        .card { border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px 10px; margin-bottom: 10px; }
        .grid td { padding: 2px 12px 2px 0; }
        .label { color: #6b7280; font-size: 8.5px; text-transform: uppercase; }
        .value { font-weight: bold; font-size: 10.5px; }
        .stats td { width: 25%; text-align: center; border: 1px solid #e5e7eb; padding: 6px; }
        .stats .num { font-size: 16px; font-weight: bold; color: #D12421; }
        table.list { width: 100%; border-collapse: collapse; }
        table.list th { background: #D12421; color: #fff; font-size: 8.5px; text-align: left; padding: 5px 4px; }
        table.list td { border-bottom: 1px solid #e5e7eb; padding: 5px 4px; vertical-align: top; }
        table.list tr:nth-child(even) td { background: #f9fafb; }
        .badge { padding: 1px 5px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .green { background: #dcfce7; color: #166534; }
        .yellow { background: #fef9c3; color: #854d0e; }
        .blue { background: #dbeafe; color: #1e40af; }
        .orange { background: #ffedd5; color: #9a3412; }
        .red { background: #fee2e2; color: #991b1b; }
        .gray { background: #f3f4f6; color: #374151; }
        .footer { position: fixed; bottom: -26px; left: 0; right: 0; font-size: 8px; color: #9ca3af; text-align: center; }
        .empty { text-align: center; padding: 18px; color: #6b7280; }
    </style>
</head>
<body>

    <div class="footer">
        Sistema de Gestión de Inventario de Dispositivos Tecnológicos · UCSC ·
        Generado el {{ $generatedAt->format('d/m/Y H:i') }} por {{ $generatedBy->name }}
    </div>

    <table class="header" width="100%">
        <tr>
            <td>
                <h1>Historial de mantenciones</h1>
                <div style="font-size: 13px; font-weight: bold; margin-top: 3px;">{{ $device->name }}</div>
                <div class="muted">{{ $device->department?->displayName() ?? $device->department?->name }}</div>
            </td>
            <td style="text-align: right;"><img src="{{ $logo }}" height="34"></td>
        </tr>
    </table>

    {{-- ficha del equipo --}}
    <div class="card">
        <table class="grid">
            <tr>
                <td><div class="label">Tipo</div><div class="value">{{ $device->deviceType?->name ?? '—' }}</div></td>
                <td><div class="label">Marca</div><div class="value">{{ $device->brand?->name ?? '—' }}</div></td>
                <td><div class="label">Procesador</div><div class="value">{{ $device->processor ? $device->processor->brand . ' ' . $device->processor->model : '—' }}</div></td>
                <td><div class="label">RAM</div><div class="value">{{ $device->ram ? $device->ram->type . ' · ' . $device->ram->capacity : '—' }}</div></td>
                <td><div class="label">Almacenamiento</div><div class="value">{{ $device->storage ? $device->storage->type . ' · ' . $device->storage->capacity : '—' }}</div></td>
                <td>
                    <div class="label">Estado actual</div>
                    @if ($device->deviceStatus)
                        <span class="badge {{ $device->deviceStatus->color() }}">{{ $device->deviceStatus->name }}</span>
                    @else
                        <div class="value">Sin estado</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- resumen --}}
    <table class="stats" width="100%" style="border-collapse: collapse; margin-bottom: 12px;">
        <tr>
            <td><div class="num">{{ $reactive }}</div><div class="muted">Fallas reportadas</div></td>
            <td><div class="num">{{ $preventive }}</div><div class="muted">Mantenciones preventivas</div></td>
            <td><div class="num">{{ $finished }}</div><div class="muted">Resueltas</div></td>
            <td><div class="num">{{ $open }}</div><div class="muted">Pendientes o en proceso</div></td>
        </tr>
    </table>

    {{-- detalle --}}
    <table class="list">
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 8%;">Detectado</th>
                <th style="width: 10%;">Mantención</th>
                <th style="width: 17%;">Error detectado</th>
                <th style="width: 6%;">Gravedad</th>
                <th style="width: 10%;">Reportó</th>
                <th style="width: 10%;">Reparó</th>
                <th style="width: 7%;">Estado</th>
                <th style="width: 8%;">Solucionado</th>
                <th style="width: 5%;">Tiempo</th>
                <th style="width: 16%;">Procedimiento realizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tickets as $ticket)
                @php
                    $isPreventive = $ticket->type === 'preventive';
                    $statusColor = match ($ticket->status) {
                        'closed', 'completed' => 'green',
                        'in_progress' => 'blue',
                        'cancelled' => 'gray',
                        default => 'yellow',
                    };
                @endphp
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $isPreventive ? 'blue' : 'orange' }}">{{ $isPreventive ? 'Preventiva' : 'Reactiva' }}</span><br>
                        <span class="muted">{{ $isPreventive ? ($ticket->maintenanceType?->name ?? '') : ucfirst($ticket->problem_type) }}</span>
                    </td>
                    <td>
                        {{ $ticket->error_description }}
                        @if ($ticket->comments)
                            <br><span class="muted">{{ $ticket->comments }}</span>
                        @endif
                    </td>
                    <td>{{ $severities[$ticket->severity] ?? '—' }}</td>
                    <td>{{ $ticket->user?->name ?? '—' }}</td>
                    <td>{{ $ticket->technician?->name ?? ($ticket->closedBy?->name ?? 'Sin asignar') }}</td>
                    <td><span class="badge {{ $statusColor }}">{{ $statuses[$ticket->status] ?? $ticket->status }}</span></td>
                    <td>{{ $ticket->closed_at?->format('d/m/Y') ?? '—' }}</td>
                    <td>{{ $duration($ticket->created_at, $ticket->closed_at) }}</td>
                    <td>{{ $ticket->solution ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="11" class="empty">Este equipo todavía no tiene mantenciones registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
