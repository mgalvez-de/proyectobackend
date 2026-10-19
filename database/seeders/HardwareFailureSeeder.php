<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HardwareFailure;

class HardwareFailureSeeder extends Seeder
{
    public function run(): void
    {
        HardwareFailure::firstOrCreate(
            ['name' => 'La pantalla no enciende'],
            ['description' => 'El monitor no muestra imagen al encender el equipo.']
        );

        HardwareFailure::firstOrCreate(
            ['name' => 'Teclado o mouse no responde'],
            ['description' => 'El equipo no reconoce el teclado o el mouse conectado.']
        );

        HardwareFailure::firstOrCreate(
            ['name' => 'Sobrecalentamiento'],
            ['description' => 'El equipo se calienta en exceso y se apaga solo.']
        );
    }
}
