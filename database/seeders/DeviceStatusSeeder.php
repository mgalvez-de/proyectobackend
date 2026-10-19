<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceStatus;

class DeviceStatusSeeder extends Seeder
{
    public function run(): void
    {
        DeviceStatus::firstOrCreate(['name' => 'Operativo']);
        DeviceStatus::firstOrCreate(['name' => 'En mantención']);
        DeviceStatus::firstOrCreate(['name' => 'Dado de baja']);
    }
}
