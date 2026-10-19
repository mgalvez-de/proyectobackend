<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ram;

class RamSeeder extends Seeder
{
    public function run(): void
    {
        Ram::firstOrCreate(['type' => 'DDR4', 'capacity' => '8 GB']);
        Ram::firstOrCreate(['type' => 'DDR4', 'capacity' => '16 GB']);
        Ram::firstOrCreate(['type' => 'DDR5', 'capacity' => '32 GB']);
    }
}
