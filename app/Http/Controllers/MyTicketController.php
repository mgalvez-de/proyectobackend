<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Tickets asignados al técnico que inició sesión
class MyTicketController extends Controller
{
    public function index()
    {
        $technician = auth()->user()->technician;

        $tickets = $technician
            ? $technician->tickets()->with('device.department')->latest()->get()
            : collect();

        return view('my-tickets.index', compact('technician', 'tickets'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $technician = auth()->user()->technician;

        // solo puede atender los tickets que tiene asignados
        abort_unless($technician && (int) $ticket->technician_id === (int) $technician->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'in_progress', 'closed'])],
            'solution' => ['nullable', 'string', 'required_if:status,closed'],
        ], [
            'status.required' => 'Selecciona el estado del ticket.',
            'solution.required_if' => 'Para cerrar el ticket debes escribir la solución aplicada.',
        ]);

        $ticket->update([
            'status' => $data['status'],
            'solution' => $data['solution'] ?? null,
            'solved_at' => $data['status'] === 'closed' ? ($ticket->solved_at ?? now()) : null,
        ]);

        return redirect()->route('my-tickets.index')->with('success', 'Ticket actualizado correctamente.');
    }
}
