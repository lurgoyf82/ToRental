<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\DB;

	class Revisione extends AlertBase
	{
		use HasFactory;

		protected $table = 'revisione';
		public static string $tableName = 'revisione';
		protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];

		public static function cancellami($search=null): \Illuminate\Pagination\LengthAwarePaginator
		{
			return Revisione::getAggregatedAlerts($search);
		}
		public static function validationRules(): array
		{
			$id_veicolo = 'required|exists:dettaglio_veicolo,id';
			$anno = 'required|date_format:Y';
			$data_pagamento = 'required|date_format:Y-m-d';
			$inizio_validita = 'required|date_format:Y-m-d';
			$fine_validita = 'required|date_format:Y-m-d|after_or_equal:inizio_validita';
			$importo = 'required|numeric|min:0';
			$agenzia = 'required|string|max:255';
			$polizza = 'required|string|max:255';
			$tipo_scadenza = 'required|in:Quadrimestrale,Semestrale,Annuale';

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza');
		}

		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato per "revisione" non è valido';
			$anno = 'L\'anno specificato per "revisione" non è valido';
			$data_pagamento = 'La data di pagamento per "revisione" non è valida';
			$inizio_validita = 'La data di inizio validità per "revisione" non è valida';
			$fine_validita = 'La data di fine validità per "revisione" non è valida o è precedente alla data di inizio validità';
			$importo = 'L\'importo per "revisione" non è valido o è negativo';
			$agenzia = 'L\'agenzia per "revisione" non è valida';
			$polizza = 'La polizza per "revisione" non è valida';
			$tipo_scadenza = 'Il tipo di scadenza per "revisione" non è valido o non è tra le opzioni consentite';

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza');
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



		public function dettaglio_veicolo()
		{
			return $this->belongsTo(DettaglioVeicolo::class, 'id_veicolo');
		}
	}
