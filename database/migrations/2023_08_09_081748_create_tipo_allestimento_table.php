<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('tipo_allestimento', function (Blueprint $table) {
			$table->id();
			$table->string('nome', 256);
			$table->timestamps();
		});

		DB::table('tipo_allestimento')->insert([
			['nome' => 'CENTINATO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MINICAR','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'CABINATO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'PORTACONTAINER','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'CARRELLONE','created_at' => NOW(),'updated_at' => NOW()]
		]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('tipo_allestimento');
	}
};
