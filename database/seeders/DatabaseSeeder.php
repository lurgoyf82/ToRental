<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tachigrafo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call(AssicurazioneTableSeeder::class);
		$this->call(AtpTableSeeder::class);
		$this->call(BolloTableSeeder::class);
		$this->call(BomboleTableSeeder::class);
		$this->call(NoleggioTableSeeder::class);
		$this->call(RevisioneTableSeeder::class);
		$this->call(TachigrafoTableSeeder::class);




		// \App\Models\User::factory(10)->create();

		// \App\Models\User::factory()->create([
		//	 'name' => 'Test User',
		//	 'email' => 'test@example.com',
		// ]);

		//seed Admin User

	}
}
