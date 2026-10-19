<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoftwareFailure;

class SoftwareFailureSeeder extends Seeder
{
    public function run(): void
    {
        SoftwareFailure::firstOrCreate(
            ['name' => 'El sistema operativo no inicia'],
            ['description' => 'El equipo se queda en la pantalla de carga o se reinicia al iniciar.']
        );

        SoftwareFailure::firstOrCreate(
            ['name' => 'Un programa se cierra inesperadamente'],
            ['description' => 'Una aplicación se cierra sola o no abre.']
        );

        SoftwareFailure::firstOrCreate(
            ['name' => 'Virus o malware detectado'],
            ['description' => 'El antivirus detecta una amenaza o el equipo muestra ventanas extrañas.']
        );
    }
}
