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
        Schema::create('targa', function (Blueprint $table) {
            $table->id(); // auto-increment primary key for the targa table
            $table->unsignedBigInteger('id_veicolo'); // This references the dettaglio_veicolo table
            $table->string('targa', 10); // Actual targa value
            $table->timestamps();  // created_at and updated_at columns

            $table->foreign('id_veicolo')->references('id')->on('dettaglio_veicolo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targa');
    }
};
