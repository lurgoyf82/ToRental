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
		Schema::create('destinazione_uso', function (Blueprint $table) {
			$table->id();
			$table->string('nome', 256);
			$table->timestamps();
		});


		DB::table('destinazione_uso')->insert([
			['nome' => 'USO TERZI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'USO PROPRIO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'USO TERZI SENZA COND.','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'USO PROPRIO / DIVENTERA\' IN LOCAZIONE','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'USO DI TERZI DA LOCARE SENZA CONDUCENTE','created_at' => NOW(),'updated_at' => NOW()]
		]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('destinazione_uso');
	}
};
