<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Validator;

	class Bollo extends AlertBase
	{
		use HasFactory;

		protected $table = 'bollo';
		public static string $tableName = 'bollo';
		protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];

		public static function getExpiringScadenzeBolli($search=null): \Illuminate\Support\Collection
		{
			if($search!=null) {
				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('bollo')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('bollo', function ($join) {
						$join->on('bollo.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('bollo.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
					->select([
						'bollo.id as id',
						DB::raw("DATE_FORMAT(bollo.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(bollo.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(bollo.fine_validita, NOW()) as livello')
					])
					->where('targa.targa', 'LIKE', '%' . $search . '%')
					->orderBy('livello', 'ASC')
					->orderBy('dettaglio_veicolo.id', 'ASC')
					->get();

			} else {

				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('bollo')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('bollo', function ($join) {
						$join->on('bollo.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('bollo.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->select([
						'bollo.id as id',
						DB::raw("DATE_FORMAT(bollo.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(bollo.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(bollo.fine_validita, NOW()) as livello')
					])
					->orderBy('livello', 'ASC')
					->orderBy('dettaglio_veicolo.id', 'ASC')
					->get();
			}

			return $query;
		}
		public static function validationRules(): array
		{
			$id_veicolo = 'required|exists:dettaglio_veicolo,id'; // Assuming 'veicolo' is the name of your vehicles table
			$anno = 'required|date_format:Y'; // Validates that the input is a year
			$data_pagamento = 'required|date_format:Y-m-d'; // Validates that the input is a date in Y-m-d format
			$inizio_validita = 'required|date_format:Y-m-d'; // Same as above
			$fine_validita = 'required|date_format:Y-m-d|after_or_equal:inizio_validita'; // Ensures fine_validita is after or on the same date as inizio_validita
			$importo = 'required|numeric|min:0'; // Validates that importo is a numeric value and not negative
			//$agenzia = 'required|string|max:255'; // Validates that agenzia is a string, and its length does not exceed 255 characters
			//$polizza = 'required|string|max:255'; // Same as above for polizza
			//$tipo_scadenza = 'required|in:Quadrimestrale,Semestrale,Annuale'; // Validates that tipo_scadenza is one of the specified options

			return compact('id_veicolo','anno','data_pagamento','inizio_validita',
				'fine_validita','importo');//,'agenzia','polizza','tipo_scadenza');
		}
		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato non è valido';
			$anno = 'L\' Anno non è valido';
			$data_pagamento = ' non è valido';
			$inizio_validita = ' non è valido';
			$fine_validita = ' non è valido';
			$importo = ' non è valido';
			//$agenzia = ' non è valido';
			//$polizza = ' non è valido';
			//$tipo_scadenza = ' non è valido';

			return compact('id_veicolo','anno','data_pagamento','inizio_validita',
				'fine_validita','importo');//,'agenzia','polizza','tipo_scadenza');
		}
		public static function validatePartial(array $data)
		{
			$rules = self::validationRules();

			$applicableRules = [];
			foreach ($data as $key => $value) {
				if (array_key_exists($key, $rules)) {
					$applicableRules[$key] = $rules[$key];
				}
			}

			return Validator::make($data, $applicableRules, self::validationMessages())->validate();
		}
	}


	/*
	SELECT
		bollo.id AS id,
		DATE_FORMAT(bollo.inizio_validita, '%d-%m-%Y') AS inizio_validita,
		DATE_FORMAT(bollo.fine_validita, '%d-%m-%Y') AS fine_validita,
		marca.id AS id_marca,
		marca.nome AS marca,
		modello.id AS id_modello,
		modello.nome AS modello,
		dettaglio_veicolo.id AS id_veicolo,
		DATEDIFF(bollo.fine_validita, NOW()) AS livello
	FROM
	dettaglio_veicolo
	LEFT JOIN (
			SELECT
				id_veicolo,
				MAX(fine_validita) AS latest_fine_validita
			FROM
				bollo
			WHERE
				inizio_validita <= NOW()
			GROUP BY
				id_veicolo
		) AS latest_revision ON latest_revision.id_veicolo= dettaglio_veicolo.id
	LEFT JOIN bollo ON bollo.id_veicolo = dettaglio_veicolo.id AND bollo.fine_validita = latest_revision.latest_fine_validita
	LEFT JOIN marca ON dettaglio_veicolo.id_marca = marca.id
	LEFT JOIN modello ON dettaglio_veicolo.id_modello = modello.id AND modello.id_marca = marca.id
	LEFT JOIN targa ON targa.id_veicolo = dettaglio_veicolo.id
	WHERE
		targa.targa LIKE CONCAT('%', $search , '%')
	ORDER BY
		livello ASC,
		dettaglio_veicolo.id ASC



	$query = DB::table('dettaglio_veicolo')
		->leftJoinSub(
			DB::table('bollo')
				->select('id_veicolo', DB::raw('MAX(fine_validita) AS latest_fine_validita'))
				->where('inizio_validita', '<=', now())
				->groupBy('id_veicolo'),
			'latest_revision',
			'latest_revision.id_veicolo',
			'=',
			'dettaglio_veicolo.id'
		)
		->leftJoin('bollo', function ($join) {
			$join->on('bollo.id_veicolo', '=', 'dettaglio_veicolo.id')
				->on('bollo.fine_validita', '=', 'latest_revision.latest_fine_validita');
		})
		->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
		->leftJoin('modello', function ($join) {
			$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
				->on('modello.id_marca', '=', 'marca.id');
		})
		->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
		->select([
			'bollo.id as id',
			DB::raw("DATE_FORMAT(bollo.inizio_validita, '%d-%m-%Y') as inizio_validita"),
			DB::raw("DATE_FORMAT(bollo.fine_validita, '%d-%m-%Y') as fine_validita"),
			'marca.id as id_marca',
			'marca.nome as marca',
			'modello.id as id_modello',
			'modello.nome as modello',
			'dettaglio_veicolo.id as id_veicolo',
			DB::raw('DATEDIFF(bollo.fine_validita, NOW()) as livello')
		])
		->where('targa.targa', 'LIKE', '%' . $search . '%')
		->orderBy('livello', 'ASC')
		->orderBy('dettaglio_veicolo.id', 'ASC')
		->get();

	 * */
