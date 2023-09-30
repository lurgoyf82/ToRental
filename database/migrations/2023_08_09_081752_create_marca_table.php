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
		Schema::create('marca', function (Blueprint $table) {
			$table->id();
			$table->string('nome', 256);
			$table->timestamps();
		});

		DB::table('marca')->insert([
			['nome' => 'CARDI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'SCANIA','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'FORD','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'IVECO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'PIAGGIO ','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'IVECO FIAT 35','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'FIAT','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MERCEDES','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'NISSAN V.I.','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'RENAULT','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'TRATTORE MAN','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'FIAT DUCATO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MITSUBISHI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MOTRICE IVECO FRIGO 60 Q.LI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'Mercedes-BENZ','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'SMART','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'NISSAN MOTOR IBERICA','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'VOLKSWAGEN','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'PEUGEOT','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'OPEL','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MOTRICE IVECO FRIGO 100 Q.LI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'AUDI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'IVECO DAILY 35','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MAN 440','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MAN 441','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MERCEDES BENZ','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MOTRICE IVECO FRIGO 180 Q.LI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MINI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'FIAT SCUDO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'IVECO STRALIS  TRATTORE SEM RIMORCHIO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'RENAULT TRATTORE ','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'LAMBORGHINI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'DAF','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'ALFA ROMEO','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'JEEP','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'LAND ROVER','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MAN','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'PORSCHE ','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'CITROEN','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'CORVETTE','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'SEAT','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'LIGIER','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'MINI CAR','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'LECITRAILER','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'OMT','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'VLASTUIN','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'WIELTON','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'BARTOLETTI','created_at' => NOW(),'updated_at' => NOW()],
			['nome' => 'KRONE','created_at' => NOW(),'updated_at' => NOW()]
		]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('marca');
	}
};
