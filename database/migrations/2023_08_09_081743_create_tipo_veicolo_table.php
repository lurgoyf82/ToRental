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
        Schema::create('tipo_veicolo', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 256);
            $table->timestamps();
        });

        DB::table('tipo_veicolo')->insert([
            ['nome' => 'SEMIRIMORCHIO','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'TRATTORE','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'AUTOVETTURA','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'MOTRICE RIBALTABILE','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'FURGONATO','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO STANDARD','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO FRIGO FNA CON SPONDA','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO FRIGO','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO RIBALTABILE','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO XXL','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO CON SPONDA','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'MOTRICE FRIGO','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO 250 CON SPONDA','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO FRIGO FRC CON SPONDA','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CASSONATO FRIGO FRC','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CARROATTREZZI','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'MOTRICE CON SPONDA','created_at' => NOW(),'updated_at' => NOW()],
            ['nome' => 'CICLOMOTORE','created_at' => NOW(),'updated_at' => NOW()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_veicolo');
    }
};
