<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Brand;
use App\Models\Department;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Processor;
use App\Models\Ram;
use App\Models\Storage;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        // 3 dispositivos por departamento: [nombre, marca, almacenamiento, ram, tipo, procesador]
        $devices = [
            'Informática' => [
                ['PC-INF-01', 'Lenovo', '512 GB', '16 GB', 'Computador de escritorio', 'Core i5-12400'],
                ['PC-INF-02', 'HP', '256 GB', '8 GB', 'Computador de escritorio', 'Ryzen 5 5600G'],
                ['NB-INF-01', 'Dell', '512 GB', '32 GB', 'Notebook', 'Core i7-12700'],
            ],
            'Biblioteca' => [
                ['PC-BIB-01', 'HP', '256 GB', '8 GB', 'Computador de escritorio', 'Core i5-12400'],
                ['PC-BIB-02', 'Lenovo', '1 TB', '8 GB', 'Computador de escritorio', 'Ryzen 5 5600G'],
                ['NB-BIB-01', 'HP', '256 GB', '8 GB', 'Notebook', 'Core i5-12400'],
            ],
            'Computación' => [
                ['PC-COM-01', 'Dell', '512 GB', '16 GB', 'Computador de escritorio', 'Core i7-12700'],
                ['PC-COM-02', 'Dell', '512 GB', '16 GB', 'Computador de escritorio', 'Core i7-12700'],
                ['NB-COM-01', 'Lenovo', '256 GB', '16 GB', 'Notebook', 'Core i5-12400'],
            ],
        ];

        // procesador y tipo solo si la tabla ya tiene esas columnas (falta la migración)
        $hasType = Schema::hasColumn('devices', 'device_type_id');
        $hasProcessor = Schema::hasColumn('devices', 'processor_id');

        foreach ($devices as $departmentName => $items) {
            $department = Department::where('name', $departmentName)->first();

            if (! $department) {
                continue;
            }

            foreach ($items as [$name, $brand, $storage, $ram, $type, $processor]) {
                $data = [
                    'brand_id' => Brand::where('name', $brand)->value('id'),
                    'storage_id' => Storage::where('capacity', $storage)->value('id'),
                    'ram_id' => Ram::where('capacity', $ram)->value('id'),
                    'department_id' => $department->id,
                ];

                if ($hasType) {
                    $data['device_type_id'] = DeviceType::where('name', $type)->value('id');
                }

                if ($hasProcessor) {
                    $data['processor_id'] = Processor::where('model', $processor)->value('id');
                }

                Device::firstOrCreate(['name' => $name], $data);
            }
        }
    }
}
