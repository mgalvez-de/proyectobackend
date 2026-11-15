<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Tickets asignados al técnico que inició sesión (reactivos y preventivos)
class MyTicketController extends Controller
{
    public function index()
    {
        $technician = auth()->user()->technician;

        $tickets = $technician
            ? $technician->tickets()->with(['device.department', 'maintenanceType', 'user'])->latest()->get()
            : collect();

        // contadores: lo que falta por hacer y lo que ya quedó en el historial
        $pendingCount = $tickets->where('status', 'pending')->count();
        $inProgressCount = $tickets->where('status', 'in_progress')->count();
        $finishedCount = $tickets->filter(fn ($ticket) => $ticket->isFinished())->count();

        return view('my-tickets.index', compact(
            'technician',
            'tickets',
            'pendingCount',
            'inProgressCount',
            'finishedCount'
        ));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $technician = auth()->user()->technician;

        // solo puede atender los tickets que tiene asignados
        abort_unless($technician && (int) $ticket->technician_id === (int) $technician->id, 403);

        // un ticket cerrado ya es parte del historial
        if ($ticket->isFinished()) {
            return back()->with('error', 'Este ticket ya está cerrado y no se puede modificar.');
        }

        // reactivo: pendiente / en proceso / cerrado — preventivo: pendiente / en proceso / completada
        $allowed = array_keys($ticket->technicianStatuses());
        $finalStatus = $ticket->isPreventive() ? 'completed' : 'closed';

        $data = $request->validate([
            'status' => ['required', Rule::in($allowed)],
            'solution' => ['nullable', 'string', 'max:2000', 'required_if:status,' . $finalStatus],
        ], [
            'status.required' => 'Selecciona el estado del ticket.',
            'status.in' => 'El estado elegido no corresponde a este tipo de ticket.',
            'solution.required_if' => 'Para cerrar el ticket debes escribir la solución aplicada.',
        ]);

        $closing = $data['status'] === $finalStatus;

        // mismo registro de cierre que el botón "Cerrar" del historial
        $ticket->update([
            'status' => $data['status'],
            'solution' => $data['solution'] ?? null,
            'closed_at' => $closing ? now() : null,
            'closed_by' => $closing ? auth()->id() : null,
        ]);

        return redirect()->route('my-tickets.index')->with('success', $closing
            ? 'Ticket cerrado correctamente. Quedó en tu historial.'
            : 'Ticket actualizado correctamente.');
    }
}
