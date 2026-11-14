<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TechnicianSeeder extends Seeder
{
    public function run(): void
    {
        // cuenta para que Carlos Muñoz inicie sesión como técnico
        $account = User::firstOrCreate(
            ['rut' => '555555555'],
            ['name' => 'Carlos Muñoz', 'email' => 'cmunoz@ucsc.cl', 'rol' => 'tecnico', 'password' => Hash::make('12345678')]
        );

        Technician::updateOrCreate(
            ['email' => 'cmunoz@ucsc.cl'],
            ['name' => 'Carlos Muñoz', 'phone' => '+56 9 1111 2222', 'specialty' => 'hardware',
             'department_id' => Department::where('name', 'Informática')->value('id'), 'user_id' => $account->id]
        );

        Technician::firstOrCreate(
            ['email' => 'vrojas@ucsc.cl'],
            ['name' => 'Valentina Rojas', 'phone' => '+56 9 3333 4444', 'specialty' => 'software',
             'department_id' => Department::where('name', 'Biblioteca')->value('id')]
        );

        // sin departamento: atiende a todos
        Technician::firstOrCreate(
            ['email' => 'fsoto@ucsc.cl'],
            ['name' => 'Felipe Soto', 'phone' => null, 'specialty' => 'redes', 'department_id' => null]
        );
    }
}
