<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

// Asignar el técnico encargado de un ticket (administrador o supervisor del departamento)
class TicketAssignmentController extends Controller
{
    public function update(Request $request, Ticket $ticket)
    {
        $ticket->load('device.department');

        abort_unless(auth()->user()->canAccessDepartment($ticket->device->department), 403);

        $data = $request->validate([
            'technician_id' => ['nullable', 'exists:technicians,id'],
        ]);

        $ticket->update(['technician_id' => $data['technician_id'] ?? null]);

        return back()->with('success', $ticket->technician_id
            ? 'Técnico asignado correctamente.'
            : 'Se quitó el técnico del ticket.');
    }
}
