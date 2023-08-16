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
        Schema::create('dettaglio_veicolo', function (Blueprint $table) {
            $table->id('id');   // auto-increment primary key for the dettaglio_veicolo table
            $table->unsignedBigInteger('id_proprietario');  // This references the societa table
            $table->unsignedBigInteger('id_tipo_veicolo');  // This references the tipo_veicolo table
            $table->unsignedBigInteger('id_tipo_allestimento'); // This references the tipo_allestimento table
            $table->unsignedBigInteger('id_marca');    // This references the marca table
            $table->unsignedBigInteger('id_modello');  // This references the modello table
            $table->string('colore', 256);  // colore value
            $table->unsignedDecimal('lunghezza_esterna', 10, 2);    // lunghezza_esterna value
            $table->unsignedDecimal('larghezza_esterna', 10, 2);    // larghezza_esterna value
            $table->unsignedDecimal('massa', 10, 2);    // massa value
            $table->unsignedInteger('portata'); // portata value
            $table->unsignedInteger('cilindrata');  // cilindrata value
            $table->unsignedInteger('potenza'); // potenza value
            $table->unsignedInteger('numero_assi'); //numero_assi value
            $table->unsignedBigInteger('tipo_asse');
            $table->unsignedBigInteger('tipo_cambio');
            $table->unsignedBigInteger('alimentazione');
            $table->dateTime('data_immatricolazione');
            $table->unsignedBigInteger('destinazione_uso');
            $table->timestamps();

            $table->foreign('id_proprietario')->references('id')->on('societa');
            $table->foreign('id_tipo_veicolo')->references('id')->on('tipo_veicolo');
            $table->foreign('id_tipo_allestimento')->references('id')->on('tipo_allestimento');
            $table->foreign('id_marca')->references('id')->on('marca');
            $table->foreign('id_modello')->references('id')->on('modello');
            $table->foreign('tipo_asse')->references('id')->on('tipo_asse');
            $table->foreign('tipo_cambio')->references('id')->on('tipo_cambio');
            $table->foreign('alimentazione')->references('id')->on('tipo_alimentazione');
            $table->foreign('destinazione_uso')->references('id')->on('destinazione_uso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dettaglio_veicolo');
    }
};
