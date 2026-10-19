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

        $departments = Department::withCount('devices')
            ->get()
            ->filter(fn ($department) => $user->canAccessDepartment($department));

        return view('dashboard', compact('departments'));
    }
}
