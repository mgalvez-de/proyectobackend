<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motherboard;

class MotherboardSeeder extends Seeder
{
    public function run(): void
    {
        Motherboard::firstOrCreate(['brand' => 'ASUS', 'model' => 'PRIME B660M-A']);
        Motherboard::firstOrCreate(['brand' => 'Gigabyte', 'model' => 'B550M DS3H']);
        Motherboard::firstOrCreate(['brand' => 'MSI', 'model' => 'PRO H610M-E']);
    }
}
