<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Veicolo extends BaseModel
	{
		use HasFactory;

		protected $table = 'dettaglio_veicolo';
		protected $fillable = ['id_proprietario','id_tipo_veicolo','id_tipo_allestimento','id_marca','id_modello','colore',
			'lunghezza_esterna','larghezza_esterna','massa','portata','cilindrata','potenza','numero_assi','tipo_asse',
			'tipo_cambio','alimentazione','destinazione_uso'];

		public function store(Request $request)
		{
			$data = $request->validate([
				'id_proprietario' => 'required|integer',
				// ... Add other fields with validation rules ...
				'colore' => 'required|string',
				// ...
			]);

			Veicolo::create($data);

			return redirect()->route('create_veicolo')->with('success', 'Veicolo created successfully!');
		}

	}
