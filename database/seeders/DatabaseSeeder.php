<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,

            // mantenedores (3 registros cada uno)
            BrandSeeder::class,
            StorageSeeder::class,
            RamSeeder::class,
            DeviceTypeSeeder::class,
            ProcessorSeeder::class,
            DeviceStatusSeeder::class,
            HardwareFailureSeeder::class,
            SoftwareFailureSeeder::class,

            // 3 dispositivos por departamento (usa los mantenedores de arriba)
            DeviceSeeder::class,
        ]);
    }
}
