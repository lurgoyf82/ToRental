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
		Schema::create('tipo_alimentazione', function (Blueprint $table) {
			$table->id();
			$table->string('nome', 256);
			$table->timestamps();
		});

		DB::table('tipo_alimentazione')->insert([
			['nome' => 'NO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'GASOLIO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'BENZINA','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'METANO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'BENZINA/METANO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'IBRIDO/BENZINA','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'DIESEL','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'BENZINA / IBRIDA','created_at' => NOW(),'updated_at' => NOW()]
		]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('tipo_alimentazione');
	}
};
