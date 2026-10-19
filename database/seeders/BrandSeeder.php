<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::firstOrCreate(['name' => 'Lenovo']);
        Brand::firstOrCreate(['name' => 'HP']);
        Brand::firstOrCreate(['name' => 'Dell']);
    }
}
