<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    public function run(): void
    {
        Storage::firstOrCreate(['type' => 'SSD', 'capacity' => '256 GB']);
        Storage::firstOrCreate(['type' => 'SSD', 'capacity' => '512 GB']);
        Storage::firstOrCreate(['type' => 'HDD', 'capacity' => '1 TB']);
    }
}
