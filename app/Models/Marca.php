<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Marca extends BaseModel
	{
		use HasFactory;

		protected $table = 'marca';
		protected $fillable = ['nome'];

		public static function validationRules(): array
		{
			$nome = 'required|string|max:256';

			return compact('nome');
		}

		//Validation Messages
		public static function validationMessages(): array
		{
			$nome = 'Il nome della marca non è valido o mancante';

			return compact('nome');
		}

	}
