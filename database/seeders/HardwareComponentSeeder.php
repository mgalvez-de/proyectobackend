<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Device;
use App\Models\HardwareComponent;

class HardwareComponentSeeder extends Seeder
{
    public function run(): void
    {
        // un componente de cada tipo: [dispositivo, tipo, marca, modelo, serie, instalación, vencimiento]
        $components = [
            ['PC-INF-01', 'motherboard', 'Lenovo', 'B550M', 'SN-MB-0001', '2024-03-10', '2027-03-10'],
            ['PC-INF-01', 'screen', 'HP', 'P24 G5', 'SN-SC-0001', '2024-03-10', '2026-12-15'],
            ['PC-INF-02', 'case', 'HP', 'ProDesk 400', 'SN-CA-0001', '2023-08-01', '2028-08-01'],
            ['PC-BIB-01', 'power_supply', 'Dell', '500W 80 Plus', 'SN-PS-0001', '2021-05-20', '2025-05-20'],
        ];

        foreach ($components as [$device, $type, $brand, $model, $serial, $installed, $expires]) {
            $deviceId = Device::where('name', $device)->value('id');

            if (! $deviceId) {
                continue;
            }

            HardwareComponent::firstOrCreate(
                ['serial_number' => $serial],
                [
                    'device_id' => $deviceId,
                    'type' => $type,
                    'brand_id' => Brand::where('name', $brand)->value('id'),
                    'model' => $model,
                    'installed_at' => $installed,
                    'expires_at' => $expires,
                ]
            );
        }
    }
}
