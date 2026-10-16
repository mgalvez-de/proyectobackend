<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;

class HistoryController extends Controller
{
    public function index($department)
    {
        $department = Department::findOrFail($department);

        $tickets = Ticket::with(['device', 'user'])
            ->whereHas('device', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })
            ->latest()
            ->get();

        return view('history.index', compact('department', 'tickets'));
    }
}
