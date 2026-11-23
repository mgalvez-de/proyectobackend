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

        $user = auth()->user();
        $soloPropios = $user->rol === 'estudiante';

        if (! $soloPropios) {
            abort_unless(
                $user->canAccessDepartment($department),
                403,
                'No tienes acceso a este departamento.'
            );
        }

        $tickets = Ticket::with(['device', 'user', 'technician', 'maintenanceType', 'closedBy'])
            ->whereHas('device', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })
            ->when($soloPropios, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        $reactiveCount = $this->countByType($department, 'reactive', $soloPropios ? $user->id : null);
        $preventiveCount = $this->countByType($department, 'preventive', $soloPropios ? $user->id : null);

        // técnicos que pueden atender este departamento (los de "Todos" también)
        $technicians = Technician::whereNull('department_id')
            ->orWhere('department_id', $department->id)
            ->orderBy('name')
            ->get();

        return view('history.index', compact(
            'department',
            'tickets',
            'reactiveCount',
            'preventiveCount',
            'soloPropios',
            'technicians'
        ));
    }

    private function countByType(Department $department, string $type, ?int $userId = null): int
    {
        return Ticket::where('type', $type)
            ->whereHas('device', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();
    }
}
