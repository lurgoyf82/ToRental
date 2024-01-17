<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Validator;

	class Multa extends BaseModel
	{
		use HasFactory;
		protected $table = 'multa';

		protected $fillable = ['id_veicolo','data_multa','importo','descrizione','pagato','data_pagamento'];
		public static string $tableName = 'multa';

		public function veicolo()
		{
			return $this->belongsTo(Veicolo::class, 'id_veicolo');
		}

		public static function getExpiringScadenzeMulte($search = null): \Illuminate\Support\Collection
		{
			if ($search != null) {
				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('multa')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('multa', function ($join) {
						$join->on('multa.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('multa.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
					->select([
						'multa.id as id',
						DB::raw("DATE_FORMAT(multa.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(multa.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(multa.fine_validita, NOW()) as livello')
					])
					->where('targa.targa', 'LIKE', '%' . $search . '%')
					->orderBy('livello', 'ASC')
					->orderBy('dettaglio_veicolo.id', 'ASC')
					->get();

			} else {

				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('multa')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('multa', function ($join) {
						$join->on('multa.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('multa.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->select([
						'multa.id as id',
						DB::raw("DATE_FORMAT(multa.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(multa.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(multa.fine_validita, NOW()) as livello')
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

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita',
				'fine_validita', 'importo');//,'agenzia','polizza','tipo_scadenza');
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

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita',
				'fine_validita', 'importo');//,'agenzia','polizza','tipo_scadenza');
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

		static function search($search, $searchField = false)
		{
			return self::query()
				->from('multa')
				->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', 'multa.id_veicolo')
				->leftJoin('societa', 'societa.id', '=', 'dettaglio_veicolo.id_proprietario')
				->leftJoin('tipo_veicolo', 'tipo_veicolo.id', '=', 'dettaglio_veicolo.id_tipo_veicolo')
				->leftJoin('tipo_allestimento', 'tipo_allestimento.id', '=', 'dettaglio_veicolo.id_tipo_allestimento')
				->leftJoin('marca', 'marca.id', '=', 'dettaglio_veicolo.id_marca')
				->leftJoin('modello', 'modello.id', '=', 'dettaglio_veicolo.id_modello')
				->leftJoin('tipo_asse', 'tipo_asse.id', '=', 'dettaglio_veicolo.tipo_asse')
				->leftJoin('tipo_cambio', 'tipo_cambio.id', '=', 'dettaglio_veicolo.tipo_cambio')
				->leftJoin('destinazione_uso', 'destinazione_uso.id', '=', 'dettaglio_veicolo.destinazione_uso')
				->leftJoin('tipo_alimentazione', 'tipo_alimentazione.id', '=', 'dettaglio_veicolo.alimentazione')
				->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
				->where(function ($query) use ($search, $searchField) {
					if ($searchField) {
						// Only search by ID
						$query->where('multa.id', '=', $search);
					} else {
						// Search across multiple fields
						$query->where('multa.id', '=', $search)
							->orWhere('multa.data_multa', 'LIKE', "%{$search}%")
							->orWhere('multa.importo', 'LIKE', "%{$search}%")
							->orWhere('multa.descrizione', 'LIKE', "%{$search}%")
							->orWhere('multa.pagato', 'LIKE', "%{$search}%")
							->orWhere('multa.data_pagamento', 'LIKE', "%{$search}%")
							->orWhere('dettaglio_veicolo.colore', 'LIKE', "%{$search}%")
							->orWhere('dettaglio_veicolo.massa', 'LIKE', "%{$search}%")
							->orWhere('dettaglio_veicolo.portata', 'LIKE', "%{$search}%")
							->orWhere('dettaglio_veicolo.cilindrata', 'LIKE', "%{$search}%")
							->orWhere('dettaglio_veicolo.potenza', 'LIKE', "%{$search}%")
							->orWhere('dettaglio_veicolo.numero_assi', 'LIKE', "%{$search}%")
							->orWhere('societa.nome', 'LIKE', "%{$search}%")
							->orWhere('tipo_veicolo.nome', 'LIKE', "%{$search}%")
							->orWhere('tipo_allestimento.nome', 'LIKE', "%{$search}%")
							->orWhere('marca.nome', 'LIKE', "%{$search}%")
							->orWhere('modello.nome', 'LIKE', "%{$search}%")
							->orWhere('destinazione_uso.nome', 'LIKE', "%{$search}%")
							->orWhere('tipo_cambio.nome', 'LIKE', "%{$search}%")
							->orWhere('tipo_asse.nome', 'LIKE', "%{$search}%")
							->orWhere('tipo_alimentazione.nome', 'LIKE', "%{$search}%")
							->orWhere('targa.targa', 'LIKE', "%{$search}%");
					}
				})
				->get([
					'multa.id as id',
					'multa.data_multa',
					'multa.importo',
					'multa.descrizione',
					'multa.pagato',
					'multa.data_pagamento',
					'dettaglio_veicolo.id as veicolo_id',
					'dettaglio_veicolo.colore as veicolo_colore',
					'dettaglio_veicolo.massa as veicolo_massa',
					'dettaglio_veicolo.portata as veicolo_portata',
					'dettaglio_veicolo.cilindrata as veicolo_cilindrata',
					'dettaglio_veicolo.potenza as veicolo_potenza',
					'dettaglio_veicolo.numero_assi as veicolo_numero_assi',
					'societa.nome as societa_nome',
					'tipo_veicolo.nome as tipo_veicolo_nome',
					'tipo_allestimento.nome as tipo_allestimento_nome',
					'marca.nome as marca_nome',
					'modello.nome as modello_nome',
					'destinazione_uso.nome as destinazione_uso_nome',
					'tipo_cambio.nome as tipo_cambio_nome',
					'tipo_asse.nome as tipo_asse_nome',
					'tipo_alimentazione.nome as tipo_alimentazione_nome',
					'targa.targa as veicolo_targa'
				]);
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

			$query = Multa::select([
				'multa.id',
				'multa.id_veicolo',
				'multa.targa',
				'multa.anno',
				'multa.data_pagamento',
				'multa.inizio_validita',
				'multa.fine_validita',
				'multa.importo',
				'multa.agenzia',
				'multa.polizza',
				'multa.tipo_scadenza',
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
				->leftJoin('dettaglio_veicolo', 'multa.id_veicolo', '=', 'dettaglio_veicolo.id')
				->leftJoin('societa', 'dettaglio_veicolo.id_proprietario', '=', 'societa.id')
				->leftJoin('tipo_veicolo', 'dettaglio_veicolo.id_tipo_veicolo', '=', 'tipo_veicolo.id')
				->leftJoin('tipo_allestimento', 'dettaglio_veicolo.id_tipo_allestimento', '=', 'tipo_allestimento.id')
				->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
				->leftJoin('modello', 'dettaglio_veicolo.id_modello', '=', 'modello.id')
				->leftJoin('tipo_asse', 'dettaglio_veicolo.tipo_asse', '=', 'tipo_asse.id')
				->leftJoin('tipo_cambio', 'dettaglio_veicolo.tipo_cambio', '=', 'tipo_cambio.id')
				->leftJoin('destinazione_uso', 'dettaglio_veicolo.destinazione_uso', '=', 'destinazione_uso.id')
				->where(function($query) use ($search) {
					$query->where('multa.id', 'LIKE', "%{$search}%")
						->orWhere('multa.id_veicolo', 'LIKE', "%{$search}%")
						->orWhere('multa.targa', 'LIKE', "%{$search}%")
						->orWhere('multa.anno', 'LIKE', "%{$search}%")
						->orWhere('multa.data_pagamento', 'LIKE', "%{$search}%")
						->orWhere('multa.inizio_validita', 'LIKE', "%{$search}%")
						->orWhere('multa.fine_validita', 'LIKE', "%{$search}%")
						->orWhere('multa.importo', 'LIKE', "%{$search}%")
						->orWhere('multa.agenzia', 'LIKE', "%{$search}%")
						->orWhere('multa.polizza', 'LIKE', "%{$search}%")
						->orWhere('multa.tipo_scadenza', 'LIKE', "%{$search}%")
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


		public function dettaglio_veicolo()
		{
			return $this->belongsTo(DettaglioVeicolo::class, 'id_veicolo');
		}
	}
