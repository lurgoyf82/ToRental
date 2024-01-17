<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Validator;

	class Gps extends AlertBase
	{
		use HasFactory;

		protected $table = 'gps';
		public static string $tableName = 'gps';
		protected $fillable = ['id_veicolo', 'numero_imei', 'seriale', 'modello', 'costruttore',
			'data_assegnazione', 'data_rimozione', 'data_acquisto', 'stato', 'note'];


		public static function getExpiringGps($search=null): \Illuminate\Support\Collection
		{
			if($search!=null) {
				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('assicurazione')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('assicurazione', function ($join) {
						$join->on('assicurazione.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('assicurazione.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
					->select([
						'assicurazione.id as id',
						DB::raw("DATE_FORMAT(assicurazione.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(assicurazione.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(assicurazione.fine_validita, NOW()) as livello')
					])
					->where('targa.targa', 'LIKE', '%' . $search . '%')
					->orderBy('livello', 'ASC')
					->orderBy('dettaglio_veicolo.id', 'ASC')
					->get();

			} else {

				$query = DB::table('dettaglio_veicolo')
					->leftJoinSub(
						DB::table('assicurazione')
							->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
							->where('inizio_validita', '<=', now())
							->groupBy('id_veicolo'),
						'latest_revision',
						'latest_revision.id_veicolo',
						'=',
						'dettaglio_veicolo.id'
					)
					->leftJoin('assicurazione', function ($join) {
						$join->on('assicurazione.id_veicolo', '=', 'dettaglio_veicolo.id')
							->on('assicurazione.fine_validita', '=', 'latest_revision.latest_fine_validita');
					})
					->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
					->leftJoin('modello', function ($join) {
						$join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
							->on('modello.id_marca', '=', 'marca.id');
					})
					->select([
						'assicurazione.id as id',
						DB::raw("DATE_FORMAT(assicurazione.inizio_validita, '%d-%m-%Y') as inizio_validita"),
						DB::raw("DATE_FORMAT(assicurazione.fine_validita, '%d-%m-%Y') as fine_validita"),
						'marca.id as id_marca',
						'marca.nome as marca',
						'modello.id as id_modello',
						'modello.nome as modello',
						'dettaglio_veicolo.id as id_veicolo',
						DB::raw('DATEDIFF(assicurazione.fine_validita, NOW()) as livello')
					])
					->orderBy('livello', 'ASC')
					->orderBy('dettaglio_veicolo.id', 'ASC')
					->get();
			}

			return $query;
		}

		public static function validationRules(): array
		{
			$id_veicolo = 'nullable|exists:dettaglio_veicolo,id';
			$numero_imei = 'required|string|min:5|max:255';
			$seriale = 'nullable|string|max:255';
			$modello = 'nullable|string|max:255';
			$costruttore = 'nullable|string|max:255';
			$data_assegnazione = 'nullable|date_format:Y-m-d';
			$data_rimozione = 'nullable|date_format:Y-m-d|after_or_equal:data_assegnazione';
			$data_acquisto = 'nullable|date_format:Y-m-d';
			$stato = 'required|in:attivo,inattivo,in_mantenimento';
			$note = 'nullable|string';

			return compact('id_veicolo', 'numero_imei', 'seriale', 'modello', 'costruttore', 'data_assegnazione', 'data_rimozione', 'data_acquisto', 'stato', 'note');
		}

		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato per il GPS non è valido';
			$numero_imei = 'Il numero IMEI del GPS è obbligatorio e deve essere una stringa valida';
			$seriale = 'Il numero di serie del GPS deve essere una stringa valida';
			$modello = 'Il modello del GPS deve essere una stringa valida';
			$costruttore = 'Il costruttore del GPS deve essere una stringa valida';
			$data_assegnazione = 'La data di assegnazione del GPS non è valida';
			$data_rimozione = 'La data di rimozione del GPS non è valida o è precedente alla data di assegnazione';
			$data_acquisto = 'La data di acquisto del GPS non è valida';
			$stato = 'Lo stato del GPS non è valido o non è tra le opzioni consentite (attivo, inattivo, in mantenimento)';
			$note = 'Le note per il GPS devono essere una stringa valida';

			return compact('id_veicolo', 'numero_imei', 'seriale', 'modello', 'costruttore', 'data_assegnazione', 'data_rimozione', 'data_acquisto', 'stato', 'note');
		}

		public static function validationRules2(): array
		{
			$seriale = 'required|string|max:255';
			$modello = 'nullable|string|max:100';
			$costruttore = 'nullable|string|max:100';
			$data_acquisto = 'nullable|date_format:Y-m-d';
			$stato = 'required|in:attivo,inattivo,in_mantenimento';

			return compact('seriale', 'modello', 'costruttore', 'data_acquisto', 'stato');
		}

		public static function validationRulesAssegnamentoGps(): array
		{
			$id_veicolo = 'required|exists:dettaglio_veicolo,id';
			$id_gps = 'required|exists:gps,id';
			$assegnato_da = 'required|date_format:Y-m-d';
			$assegnato_a = 'nullable|date_format:Y-m-d|after_or_equal:assegnato_da';

			return compact('id_veicolo', 'id_gps', 'assegnato_da', 'assegnato_a');
		}

		public static function validationMessages2(): array
		{
			$seriale = 'Il seriale del GPS non è valido o mancante';
			$modello = 'Il modello del GPS non è valido';
			$costruttore = 'Il costruttore del GPS non è valido';
			$data_acquisto = 'La data di acquisto del GPS non è valida';
			$stato = 'Lo stato del GPS non è valido o non è tra le opzioni consentite';

			return compact('seriale', 'modello', 'costruttore', 'data_acquisto', 'stato');
		}

		public static function validationMessagesAssegnamentoGps(): array
		{
			$id_veicolo = 'Il veicolo selezionato per l\'assegnamento del GPS non è valido';
			$id_gps = 'Il GPS selezionato per l\'assegnamento non è valido';
			$assegnato_da = 'La data di inizio assegnamento del GPS non è valida';
			$assegnato_a = 'La data di fine assegnamento del GPS non è valida o è precedente alla data di inizio assegnamento';

			return compact('id_veicolo', 'id_gps', 'assegnato_da', 'assegnato_a');
		}


		static function search($search, $searchField = false)
		{
			return self::query()
				->from('gps')
				->leftJoin('assegnamento_gps', 'assegnamento_gps.id_gps', '=', 'gps.id')
				->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', 'assegnamento_gps.id_veicolo')
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
						$query->where('gps.id', '=', $search)
							->orWhere('dettaglio_veicolo.id', '=', $search);
					} else {
						// Search across multiple fields
						$query->where('gps.id', '=', $search)
							->orWhere('gps.anno', 'LIKE', "%{$search}%")
							->orWhere('gps.data_pagamento', 'LIKE', "%{$search}%")
							->orWhere('gps.inizio_validita', 'LIKE', "%{$search}%")
							->orWhere('gps.fine_validita', 'LIKE', "%{$search}%")
							->orWhere('gps.importo', 'LIKE', "%{$search}%")
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
					'gps.id',
					'gps.anno as gps_anno',
					'gps.data_pagamento as gps_data_pagamento',
					'gps.inizio_validita as gps_inizio_validita',
					'gps.fine_validita as gps_fine_validita',
					'gps.importo as gps_importo',
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

			$query = Gps::select([
				'gps.id',
				'gps.id_veicolo',
				'gps.targa',
				'gps.anno',
				'gps.data_pagamento',
				'gps.inizio_validita',
				'gps.fine_validita',
				'gps.importo',
				'gps.agenzia',
				'gps.polizza',
				'gps.tipo_scadenza',
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
				->leftJoin('dettaglio_veicolo', 'gps.id_veicolo', '=', 'dettaglio_veicolo.id')
				->leftJoin('societa', 'dettaglio_veicolo.id_proprietario', '=', 'societa.id')
				->leftJoin('tipo_veicolo', 'dettaglio_veicolo.id_tipo_veicolo', '=', 'tipo_veicolo.id')
				->leftJoin('tipo_allestimento', 'dettaglio_veicolo.id_tipo_allestimento', '=', 'tipo_allestimento.id')
				->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
				->leftJoin('modello', 'dettaglio_veicolo.id_modello', '=', 'modello.id')
				->leftJoin('tipo_asse', 'dettaglio_veicolo.tipo_asse', '=', 'tipo_asse.id')
				->leftJoin('tipo_cambio', 'dettaglio_veicolo.tipo_cambio', '=', 'tipo_cambio.id')
				->leftJoin('destinazione_uso', 'dettaglio_veicolo.destinazione_uso', '=', 'destinazione_uso.id')
				->where(function($query) use ($search) {
					$query->where('gps.id', 'LIKE', "%{$search}%")
						->orWhere('gps.id_veicolo', 'LIKE', "%{$search}%")
						->orWhere('gps.targa', 'LIKE', "%{$search}%")
						->orWhere('gps.anno', 'LIKE', "%{$search}%")
						->orWhere('gps.data_pagamento', 'LIKE', "%{$search}%")
						->orWhere('gps.inizio_validita', 'LIKE', "%{$search}%")
						->orWhere('gps.fine_validita', 'LIKE', "%{$search}%")
						->orWhere('gps.importo', 'LIKE', "%{$search}%")
						->orWhere('gps.agenzia', 'LIKE', "%{$search}%")
						->orWhere('gps.polizza', 'LIKE', "%{$search}%")
						->orWhere('gps.tipo_scadenza', 'LIKE', "%{$search}%")
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
