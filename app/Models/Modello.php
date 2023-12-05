<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Modello extends BaseModel
	{
		use HasFactory;

		protected $table = 'modello';
		protected $fillable = ['nome', 'id_marca'];

		public static function validationRules(): array
		{
			$nome = 'required|string|max:256';
			$id_marca = 'required|exists:marca,id';

			return compact('nome', 'id_marca');
		}

		//Validation Messages
		public static function validationMessages(): array
		{
			$nome = 'Il nome del modello non è valido o mancante';
			$id_marca = 'La marca selezionata per il modello non è valida';

			return compact('nome', 'id_marca');
		}

	}
