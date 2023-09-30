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
		Schema::create('veicolo', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('stato_id');
			$table->timestamps();

			// Assuming you have a 'stati' table that contains possible states for a vehicle.
			$table->foreign('stato_id')->references('id')->on('stato_veicolo');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('veicolo');
	}
};
