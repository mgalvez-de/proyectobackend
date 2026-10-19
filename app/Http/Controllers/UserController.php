<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('department')->get();
        $departments = Department::all();

        return view('users.index', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        User::create([
            'name' => $request->name,
            'rut' => $request->rut,
            'email' => $request->email,
            'rol' => $request->rol,
            'department_id' => $request->rol === 'supervisor' ? $request->department_id : null,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate($this->rules($user), $this->messages());

        if ($user->is(auth()->user()) && $request->rol !== 'admin') {
            return redirect()->route('users.index')->with('error', 'No puedes quitarte el rol de administrador.');
        }

        $data = [
            'name' => $request->name,
            'rut' => $request->rut,
            'email' => $request->email,
            'rol' => $request->rol,
            'department_id' => $request->rol === 'supervisor' ? $request->department_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    private function rules(?User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'rut' => ['required', 'regex:/^[0-9]{7,8}[0-9kK]$/', Rule::unique('users', 'rut')->ignore($user?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'rol' => ['required', Rule::in(['admin', 'supervisor', 'estudiante'])],
            'department_id' => ['nullable', 'required_if:rol,supervisor', 'exists:departments,id'],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ];
    }

    private function messages(): array
    {
        return [
            'rut.regex' => 'El RUT debe ir sin puntos ni guion. Ej: 222208481',
            'rut.unique' => 'Ya existe un usuario con ese RUT.',
            'email.unique' => 'Ya existe un usuario con ese correo.',
            'department_id.required_if' => 'Debes asignarle un departamento al supervisor.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
