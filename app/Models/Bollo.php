<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Validator;

	class Bollo extends AlertBase
	{
		use HasFactory;

		protected $table = 'bollo';
		public static string $tableName = 'bollo';
		protected $fillable = ['id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo'];

		public static function getExpiringScadenzeBolli($search = null): \Illuminate\Support\Collection
		{
			if ($search != null) {
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
			$id_veicolo = 'required|exists:dettaglio_veicolo,id';
			$anno = 'required|date_format:Y';
			$data_pagamento = 'required|date_format:Y-m-d';
			$inizio_validita = 'required|date_format:Y-m-d';
			$fine_validita = 'required|date_format:Y-m-d|after_or_equal:inizio_validita';
			$importo = 'required|numeric|min:0';

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo');
		}

		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato per "bollo" non è valido';
			$anno = 'L\'anno specificato per "bollo" non è valido';
			$data_pagamento = 'La data di pagamento per "bollo" non è valida';
			$inizio_validita = 'La data di inizio validità per "bollo" non è valida';
			$fine_validita = 'La data di fine validità per "bollo" non è valida o è precedente alla data di inizio validità';
			$importo = 'L\'importo per "bollo" non è valido o è negativo';

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo');
		}

		public function dettaglio_veicolo()
		{
			return $this->belongsTo(DettaglioVeicolo::class, 'id_veicolo');
		}

		public static function index($search = null, $order = 'livello', $page = 1, $slice = true) {
			$page = intval($page);
			if ($page <= 0 || !is_int($page)) {
				$page = 1;
			}

			$order=explode('_',$order);

			switch (strtolower($order[0])) {
				case 'marca':
					$orderBy = Marca::getTableName() . '.nome';
					break;
				case 'modello':
					$orderBy = Modello::getTableName() . '.nome';
					break;
				case 'targa':
					$orderBy = Targa::getTableName() . '.targa';
					break;
				default:
					$orderBy = 'id_veicolo';
					break;
			}

			if(array_key_exists(1,$order,)&&strtolower($order[1])=='desc') {
				$orderDirection='DESC';
			} else {
				$orderDirection='ASC';
			}

			$query=Bollo::whereHas('dettaglio_veicolo', function($query) use ($search) {
				$query->withCommonRelationships($search);
			})->orWhere('id', '=', $search)
				->orWhere('id_veicolo', '=', $search)
				->orWhere('anno', 'LIKE', "%{$search}%")
				->orWhere('data_pagamento', 'LIKE', "%{$search}%")
				->orWhere('inizio_validita', 'LIKE', "%{$search}%")
				->orWhere('fine_validita', 'LIKE', "%{$search}%")
				->orWhere('importo', 'LIKE', "%{$search}%");

			if ($orderBy!=='id_veicolo') {
				$query = $query->orderBy($orderBy, $orderDirection)->get();
			} else {
				$query = $query->orderBy('id_veicolo', 'ASC')->get();
			}

			if ($orderBy=='id_veicolo') {
				if($orderDirection=='DESC') {
					$query=($query->sortByDesc('id_veicolo'));
				} else {
					$query=($query->sortBy('id_veicolo'));
				}
			}

			// Manually slice the results for pagination
			$offset = ($page - 1) * AlertBase::$itemsPerPage;
			if($slice) {
				$itemsForCurrentPage = $query->slice($offset, AlertBase::$itemsPerPage);
			} else {
				$itemsForCurrentPage = $query;
			}

			return new LengthAwarePaginator(
				$itemsForCurrentPage,
				$query->count(),
				AlertBase::$itemsPerPage,
				$page,
				['path' => LengthAwarePaginator::resolveCurrentPath()]
			);
		}
	}
