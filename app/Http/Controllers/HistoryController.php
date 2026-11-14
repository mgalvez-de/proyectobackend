<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Technician;
use App\Models\Ticket;

class HistoryController extends Controller
{
    public function index($department)
    {
        $department = Department::findOrFail($department);

        $tickets = Ticket::with(['device', 'user', 'technician'])
            ->whereHas('device', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })
            ->latest()
            ->get();

        // técnicos que pueden atender este departamento (los de "Todos" también)
        $technicians = Technician::whereNull('department_id')
            ->orWhere('department_id', $department->id)
            ->orderBy('name')
            ->get();

        return view('history.index', compact('department', 'tickets', 'technicians'));
    }
}
