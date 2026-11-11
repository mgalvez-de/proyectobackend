<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Ticket;
use Illuminate\Http\Request;

class PreventiveMaintenanceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => ['required', 'exists:devices,id'],
            'maintenance_type_id' => ['required', 'exists:maintenance_types,id'],
            'scheduled_at' => ['required', 'date'],
            'error_description' => ['required', 'string'],
            'comments' => ['nullable', 'string'],
        ]);

        $device = Device::findOrFail($validated['device_id']);

        $this->authorizeDevice($device);

        Ticket::create([
            'device_id' => $device->id,
            'user_id' => auth()->id(),
            'type' => 'preventive',
            'maintenance_type_id' => $validated['maintenance_type_id'],
            'error_description' => $validated['error_description'],
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'pending',
            'comments' => $validated['comments'] ?? null,
        ]);

        return redirect()
            ->route('departments.history', $device->department_id)
            ->with('success', 'Mantención preventiva programada correctamente.');
    }

    public function update(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->isPreventive(), 404);

        $this->authorizeDevice($ticket->device);

        $validated = $request->validate([
            'status' => ['required', 'in:completed,cancelled'],
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'closed_at' => now(),
            'closed_by' => auth()->id(),
        ]);

        $mensaje = $validated['status'] === 'completed'
            ? 'Mantención preventiva marcada como completada.'
            : 'Mantención preventiva cancelada.';

        return back()->with('success', $mensaje);
    }

    private function authorizeDevice(Device $device): void
    {
        abort_unless(
            auth()->user()->canAccessDepartment($device->department),
            403,
            'No tienes acceso a este departamento.'
        );
    }
}
