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
			['email' => 'admin@admin.cl'],
			['name' => 'Administrador',
			 'password' => Hash::make('12345678'),
			]
		);

		User::updateOrCreate(
			['email' => 'super@admin.cl'],
			['name' => 'SuperAdmin',
			 'password' => Hash::make('12345678'),
			]
		);
	}
}