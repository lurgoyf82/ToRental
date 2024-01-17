<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\DB;

	class Atp extends AlertBase
	{
		use HasFactory;

		protected $table = 'atp';
		public static string $tableName = 'atp';
		protected $fillable = ['id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo'];

		public static function getExpiringRevisioniAtp($search = null): \Illuminate\Support\Collection
		{
			if ($search != null) {
				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('atp')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('atp', function ($join) {
						$join->on('atp.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('atp.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
					->select([
						'atp.id as id',
						DB::raw("DATE_FORMAT(atp.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(atp.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(atp.fine_validita, NOW()) as livello')
					])
					->where('targa.targa', 'LIKE', '%' . $search . '%')
					->orderBy('livello', 'ASC')
					->orderBy('dettaglio_veicolo.id', 'ASC')
					->get();

			} else {

				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('atp')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('atp', function ($join) {
						$join->on('atp.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('atp.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->select([
						'atp.id as id',
						DB::raw("DATE_FORMAT(atp.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(atp.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(atp.fine_validita, NOW()) as livello')
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

		//Validation Messages
		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato per "Atp" non è valido';
			$anno = 'L\'anno specificato per "Atp" non è valido';
			$data_pagamento = 'La data di pagamento per "Atp" non è valida';
			$inizio_validita = 'La data di inizio validità per "Atp" non è valida';
			$fine_validita = 'La data di fine validità per "Atp" non è valida o è precedente alla data di inizio validità';
			$importo = 'L\'importo per "Atp" non è valido o è negativo';

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo');
		}



		public static function search($search, $searchField = false)
		{
			$query = self::query()
				->from(self::$tableName)
				->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', self::$tableName.'.id_veicolo');
			$query = self::commonJoins($query)->where(function ($query) use ($search, $searchField) {
				$scopeSearch = self::scopeSearch($query, $search, $searchField);
				if ($scopeSearch == null) {
					// General search across multiple fields
					$query->where(self::$tableName.'.id', '=', $search);
					$query=self::commonWheres($query,$search);
				} else {
					$query = $scopeSearch;
				}
			});

			$results = $query->get(array_merge([
				self::$tableName.'.id'], self::commonSelect()));

			return $results;
		}


		public static function index($search = null, $order = 'livello', $page = 1, $slice = true) {
			$page = intval($page);
			if ($page <= 0 || !is_int($page)) {
				$page = 1;
			}

			$order=explode('_',$order);

			switch (strtolower($order[0])) {
				case 'marca':
					$orderBy = 'marca.nome';
					break;
				case 'modello':
					$orderBy = 'modello.nome';
					break;
				case 'targa':
					$orderBy = 'targa.targa';
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

			$query = Atp::select([
				'atp.id',
				'atp.id_veicolo',
				//'atp.targa',
				'atp.anno',
				'atp.data_pagamento',
				'atp.inizio_validita',
				'atp.fine_validita',
				'atp.importo',
				'atp.agenzia',
				'atp.polizza',
				'atp.tipo_scadenza',
				'dettaglio_veicolo.id_proprietario',
				'dettaglio_veicolo.id_tipo_veicolo',
				'dettaglio_veicolo.id_tipo_allestimento',
				'dettaglio_veicolo.id_marca',
				'dettaglio_veicolo.id_modello',
				'dettaglio_veicolo.colore',
				'dettaglio_veicolo.lunghezza_esterna',
				'dettaglio_veicolo.larghezza_esterna',
				'dettaglio_veicolo.massa',
				'dettaglio_veicolo.portata',
				'dettaglio_veicolo.cilindrata',
				'dettaglio_veicolo.potenza',
				'dettaglio_veicolo.numero_assi',
				'dettaglio_veicolo.tipo_asse',
				'dettaglio_veicolo.tipo_cambio',
				'dettaglio_veicolo.alimentazione',
				'dettaglio_veicolo.destinazione_uso',
				'dettaglio_veicolo.altre_caratteristiche',
				'dettaglio_veicolo.data_acquisto',
				'dettaglio_veicolo.note_acquisto',
				'dettaglio_veicolo.prezzo',
				'dettaglio_veicolo.data_vendita',
				'dettaglio_veicolo.controparte_vendita',
				'societa.nome as societa_nome',
				'tipo_veicolo.nome as tipo_veicolo_nome',
				'tipo_allestimento.nome as tipo_allestimento_nome',
				'marca.nome as marca_nome',
				'modello.nome as modello_nome',
				'tipo_asse.nome as tipo_asse_nome',
				'tipo_cambio.nome as tipo_cambio_nome',
				'destinazione_uso.nome as destinazione_uso_nome'
			])
				->leftJoin('dettaglio_veicolo', 'atp.id_veicolo', '=', 'dettaglio_veicolo.id')
				->leftJoin('targa', 'atp.id_veicolo', '=', 'targa.id_veicolo')
				->leftJoin('societa', 'dettaglio_veicolo.id_proprietario', '=', 'societa.id')
				->leftJoin('tipo_veicolo', 'dettaglio_veicolo.id_tipo_veicolo', '=', 'tipo_veicolo.id')
				->leftJoin('tipo_allestimento', 'dettaglio_veicolo.id_tipo_allestimento', '=', 'tipo_allestimento.id')
				->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
				->leftJoin('modello', 'dettaglio_veicolo.id_modello', '=', 'modello.id')
				->leftJoin('tipo_asse', 'dettaglio_veicolo.tipo_asse', '=', 'tipo_asse.id')
				->leftJoin('tipo_cambio', 'dettaglio_veicolo.tipo_cambio', '=', 'tipo_cambio.id')
				->leftJoin('destinazione_uso', 'dettaglio_veicolo.destinazione_uso', '=', 'destinazione_uso.id')
				->where(function($query) use ($search) {
					$query->where('atp.id', 'LIKE', "%{$search}%")
						->orWhere('atp.id_veicolo', 'LIKE', "%{$search}%")
						->orWhere('atp.targa', 'LIKE', "%{$search}%")
						->orWhere('atp.anno', 'LIKE', "%{$search}%")
						->orWhere('atp.data_pagamento', 'LIKE', "%{$search}%")
						->orWhere('atp.inizio_validita', 'LIKE', "%{$search}%")
						->orWhere('atp.fine_validita', 'LIKE', "%{$search}%")
						->orWhere('atp.importo', 'LIKE', "%{$search}%")
						->orWhere('atp.agenzia', 'LIKE', "%{$search}%")
						->orWhere('atp.polizza', 'LIKE', "%{$search}%")
						->orWhere('atp.tipo_scadenza', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.colore', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.lunghezza_esterna', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.larghezza_esterna', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.massa', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.portata', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.cilindrata', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.potenza', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.numero_assi', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.tipo_asse', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.tipo_cambio', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.alimentazione', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.destinazione_uso', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.altre_caratteristiche', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.data_acquisto', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.note_acquisto', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.prezzo', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.data_vendita', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.controparte_vendita', 'LIKE', "%{$search}%")
						->orWhere('societa.nome', 'LIKE', "%{$search}%")
						->orWhere('tipo_veicolo.nome', 'LIKE', "%{$search}%")
						->orWhere('tipo_allestimento.nome', 'LIKE', "%{$search}%")
						->orWhere('marca.nome', 'LIKE', "%{$search}%")
						->orWhere('tipo_asse.nome', 'LIKE', "%{$search}%")
						->orWhere('tipo_cambio.nome', 'LIKE', "%{$search}%")
						->orWhere('destinazione_uso.nome', 'LIKE', "%{$search}%")
						->orWhereExists(function ($query) use ($search) {
							$query->select(DB::raw(1))
								->from('targa')
								->whereRaw('dettaglio_veicolo.id = targa.id_veicolo')
								->where('targa.targa', 'LIKE', "%{$search}%");
						})
						->orWhereExists(function ($query) use ($search) {
							$query->select(DB::raw(1))
								->from('telaio')
								->whereRaw('dettaglio_veicolo.id = telaio.id_veicolo')
								->where('telaio.telaio', 'LIKE', "%{$search}%");
						});
				});

			if ($orderBy!=='id_veicolo') {
				$results = $query->orderBy($orderBy, $orderDirection)->get();
			} else {
				$results = $query->get();
				if($orderDirection!=='DESC') {
					$results=($results->sortByDesc('id_veicolo'));
				} else {
					$results=($results->sortBy('id_veicolo'));
				}
			}

			// Manually slice the results for pagination
			$offset = ($page - 1) * AlertBase::$itemsPerPage;
			if($slice) {
				$itemsForCurrentPage = $results->slice($offset, AlertBase::$itemsPerPage);
			} else {
				$itemsForCurrentPage = $results;
			}

			return new LengthAwarePaginator(
				$itemsForCurrentPage,
				$results->count(),
				AlertBase::$itemsPerPage,
				$page,
				['path' => LengthAwarePaginator::resolveCurrentPath()]
			);
		}

	}
