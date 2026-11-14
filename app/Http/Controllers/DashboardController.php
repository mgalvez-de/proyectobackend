<?php

namespace App\Http\Controllers;

use App\Models\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->rol === 'supervisor' && $user->department_id) {
            return redirect()->route('departments.show', $user->department_id);
        }

        // el técnico entra directo a los tickets que tiene asignados
        if ($user->rol === 'tecnico') {
            return redirect()->route('my-tickets.index');
        }

        $departments = Department::withCount('devices')
            ->get()
            ->filter(fn ($department) => $user->canAccessDepartment($department));

        return view('dashboard', compact('departments'));
    }
}
