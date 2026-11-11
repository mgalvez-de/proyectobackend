<?php

namespace App\Http\Controllers;

use App\Models\Department;
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

        $tickets = Ticket::with(['device', 'user', 'maintenanceType', 'closedBy'])
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

        return view('history.index', compact(
            'department',
            'tickets',
            'reactiveCount',
            'preventiveCount',
            'soloPropios'
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
