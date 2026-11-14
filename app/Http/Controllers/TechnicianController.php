<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TechnicianController extends Controller
{
    public function index()
    {
        $technicians = Technician::with(['department', 'user'])->get();
        $departments = Department::all();
        $specialties = Technician::SPECIALTIES;

        // cuentas con rol "tecnico" que se pueden vincular a una ficha
        $accounts = User::where('rol', 'tecnico')->orderBy('name')->get();

        return view('technicians.index', compact('technicians', 'departments', 'specialties', 'accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        Technician::create($data);

        return redirect()->route('technicians.index')->with('success', 'Técnico creado correctamente.');
    }

    public function update(Request $request, Technician $technician)
    {
        $data = $request->validate($this->rules($technician), $this->messages());

        $technician->update($data);

        return redirect()->route('technicians.index')->with('success', 'Técnico actualizado correctamente.');
    }

    public function destroy(Technician $technician)
    {
        $technician->delete();

        return redirect()->route('technicians.index')->with('success', 'Técnico eliminado correctamente.');
    }

    private function rules(?Technician $technician = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('technicians', 'email')->ignore($technician?->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialty' => ['required', Rule::in(array_keys(Technician::SPECIALTIES))],
            'department_id' => ['nullable', 'exists:departments,id'],
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('rol', 'tecnico'),
                Rule::unique('technicians', 'user_id')->ignore($technician?->id),
            ],
        ];
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Ingresa el nombre del técnico.',
            'email.required' => 'Ingresa el correo del técnico.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.unique' => 'Ya existe un técnico con ese correo.',
            'specialty.required' => 'Selecciona la especialidad.',
            'specialty.in' => 'La especialidad seleccionada no es válida.',
            'department_id.exists' => 'El departamento seleccionado no existe.',
            'user_id.exists' => 'La cuenta seleccionada no tiene el rol de técnico.',
            'user_id.unique' => 'Esa cuenta ya está vinculada a otro técnico.',
        ];
    }
}
