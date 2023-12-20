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
//			$validator = Validator::make($request->all(), self::validationRules(), self::validationMessages());
//			if ($validator->fails()) {
//				return response()->json(['errors' => $validator->errors()], 422);
//			}
//			$veicolo = new Veicolo($validator->validated());
//			$veicolo->save();
//			return response()->json(['message' => 'Veicolo inserito correttamente'], 200);
		}

	}
