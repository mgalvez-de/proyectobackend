<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Processor;

class ProcessorSeeder extends Seeder
{
    public function run(): void
    {
        Processor::firstOrCreate(['brand' => 'Intel', 'model' => 'Core i5-12400']);
        Processor::firstOrCreate(['brand' => 'Intel', 'model' => 'Core i7-12700']);
        Processor::firstOrCreate(['brand' => 'AMD', 'model' => 'Ryzen 5 5600G']);
    }
}
