<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

// Historial del estudiante: todos los tickets que reportó, de cualquier departamento
class MyReportController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['device.department', 'technician', 'closedBy'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // contadores de las tarjetas de arriba
        $pendingCount = $tickets->where('status', 'pending')->count();
        $inProgressCount = $tickets->where('status', 'in_progress')->count();
        $closedCount = $tickets->whereIn('status', ['closed', 'completed'])->count();

        return view('my-reports.index', compact(
            'tickets',
            'pendingCount',
            'inProgressCount',
            'closedCount'
        ));
    }
}
