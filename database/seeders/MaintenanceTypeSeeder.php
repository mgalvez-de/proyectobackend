<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceType;

class MaintenanceTypeSeeder extends Seeder
{
    public function run(): void
    {
        MaintenanceType::firstOrCreate(
            ['name' => 'Limpieza interna'],
            ['description' => 'Limpieza de polvo en ventiladores, disipadores y fuente de poder.']
        );

        MaintenanceType::firstOrCreate(
            ['name' => 'Actualización de software'],
            ['description' => 'Instalación de actualizaciones del sistema operativo y de los programas.']
        );

        MaintenanceType::firstOrCreate(
            ['name' => 'Revisión de hardware'],
            ['description' => 'Revisión del estado de los componentes y de sus conexiones.']
        );
    }
}
