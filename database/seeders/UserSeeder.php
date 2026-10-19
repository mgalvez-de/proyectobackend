<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $informatica = Department::where('name', 'Informática')->first();
        $biblioteca = Department::where('name', 'Biblioteca')->first();
        $computacion = Department::where('name', 'Computación')->first();

        User::updateOrCreate(
            ['rut' => '222208481'],
            ['name' => 'Administrador', 'email' => 'admin@ucsc.cl', 'rol' => 'admin', 'department_id' => null, 'password' => Hash::make('12345678')]
        );

        User::updateOrCreate(
            ['rut' => '111111111'],
            ['name' => 'Supervisor Informática', 'email' => 'supervisor.informatica@ucsc.cl', 'rol' => 'supervisor', 'department_id' => $informatica->id, 'password' => Hash::make('12345678')]
        );

        User::updateOrCreate(
            ['rut' => '333333333'],
            ['name' => 'Supervisor Biblioteca', 'email' => 'supervisor.biblioteca@ucsc.cl', 'rol' => 'supervisor', 'department_id' => $biblioteca->id, 'password' => Hash::make('12345678')]
        );

        User::updateOrCreate(
            ['rut' => '444444444'],
            ['name' => 'Supervisor Computación', 'email' => 'supervisor.computacion@ucsc.cl', 'rol' => 'supervisor', 'department_id' => $computacion->id, 'password' => Hash::make('12345678')]
        );

        User::updateOrCreate(
            ['rut' => '222222222'],
            ['name' => 'Estudiante', 'email' => 'estudiante@ucsc.cl', 'rol' => 'estudiante', 'department_id' => null, 'password' => Hash::make('12345678')]
        );
    }
}
