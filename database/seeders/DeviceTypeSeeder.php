<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceType;

class DeviceTypeSeeder extends Seeder
{
    public function run(): void
    {
        DeviceType::firstOrCreate(['name' => 'Computador de escritorio']);
        DeviceType::firstOrCreate(['name' => 'Notebook']);
        DeviceType::firstOrCreate(['name' => 'Impresora']);
    }
}
