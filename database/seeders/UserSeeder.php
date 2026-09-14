<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{

		User::updateOrCreate(
			['rut' => '222208481'],
			['name' => 'Administrador', 'email' => 'admin@ucsc.cl', 'rol' => 'admin', 'password' => Hash::make('12345678')]
		);

		User::updateOrCreate(
			['rut' => '111111111'],
			['name' => 'Supervisor', 'email' => 'supervisor@ucsc.cl', 'rol' => 'supervisor', 'password' => Hash::make('12345678')]
		);

		User::updateOrCreate(
			['rut' => '222222222'],
			['name' => 'Estudiante', 'email' => 'estudiante@ucsc.cl', 'rol' => 'estudiante', 'password' => Hash::make('12345678')]
		);

	}
}
