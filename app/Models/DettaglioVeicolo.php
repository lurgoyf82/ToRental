<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Request;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Validator;

	class DettaglioVeicolo extends BaseModel
	{
		use HasFactory;

		protected $table = 'dettaglio_veicolo';
		protected $fillable = [
			'id_proprietario', 'id_tipo_veicolo', 'id_tipo_allestimento', 'id_marca', 'id_modello',
			'colore', 'lunghezza_esterna', 'larghezza_esterna', 'massa', 'portata', 'cilindrata',
			'potenza', 'numero_assi', 'tipo_asse', 'tipo_cambio', 'alimentazione', 'destinazione_uso'
		];
		public $timestamps = true;

		/**  /

		public static function validationRules(): array
		{
			//$targa = ['required', 'regex:/^[A-Za-z]{2}\s?\d{3}\s?[A-Za-z]{2}$/', 'unique:targa,targa'];
			$targa = ['required', 'regex:/^[A-Z0-9]{5,}$/i', 'unique:targa,targa'];
			$data_immatricolazione = 'required|date_format:Y-m-d';
			$id_proprietario = 'required|exists:societa,id';
			$id_tipo_veicolo = 'required|exists:tipo_veicolo,id';
			$id_tipo_allestimento = 'required|exists:tipo_allestimento,id';
			$id_marca = 'required|exists:marca,id';
			$id_modello = 'required|exists:modello,id';
			$id_numero_assi = 'required|integer|min:1|max:4';
			$id_tipo_asse = 'required|exists:tipo_asse,id';
			$id_tipo_cambio = 'required|exists:tipo_cambio,id';
			$id_tipo_alimentazione = 'required|exists:tipo_alimentazione,id';
			$id_destinazione_uso = 'required|exists:destinazione_uso,id';
			$colore = 'nullable|string|max:256';
			$lunghezza_esterna = 'nullable|numeric|min:0';
			$larghezza_esterna = 'nullable|numeric|min:0';
			$massa = 'nullable|numeric|min:0';
			$portata = 'nullable|numeric|min:0';
			$cilindrata = 'nullable|numeric|min:0';
			$potenza = 'nullable|numeric|min:0';
			$numero_assi = 'nullable|integer|min:0';
			$tipo_asse = 'nullable|exists:tipo_asse,id';
			$tipo_cambio = 'nullable|exists:tipo_cambio,id';
			$alimentazione = 'nullable|exists:tipo_alimentazione,id';
			$destinazione_uso = 'nullable|exists:destinazione_uso,id';

			return compact('targa', 'data_immatricolazione', 'id_proprietario', 'id_tipo_veicolo', 'id_tipo_allestimento',
				'id_marca', 'id_modello','id_numero_assi', 'id_tipo_asse', 'id_tipo_cambio', 'id_tipo_alimentazione', 'id_destinazione_uso',
				'colore', 'lunghezza_esterna', 'larghezza_esterna', 'massa', 'portata', 'cilindrata', 'potenza', 'numero_assi', 'tipo_asse',
				'tipo_cambio', 'alimentazione', 'destinazione_uso');
		}

		public static function validationMessages(): array
		{
			$targa = 'Inserire la targa (Controllare non sia già stata inserita)';
			$data_immatricolazione = 'Inserisci la data di immatricolazione';
			$id_numero_assi = 'Scegliere un numero assi';
			$id_tipo_asse = 'Scegliere un tipo asse';
			$id_tipo_cambio = 'Scegliere un tipo cambio';
			$id_tipo_alimentazione = 'Scegliere un tipo alimentazione';
			$id_destinazione_uso = 'Scegliere una destinazione uso';
			$id_proprietario = 'Il proprietario selezionato per "dettaglio veicolo" non è valido';
			$id_tipo_veicolo = 'Il tipo di veicolo selezionato per "dettaglio veicolo" non è valido';
			$id_tipo_allestimento = 'Il tipo di allestimento selezionato per "dettaglio veicolo" non è valido';
			$id_marca = 'La marca selezionata per "dettaglio veicolo" non è valida';
			$id_modello = 'Il modello selezionato per "dettaglio veicolo" non è valido';
			$colore = 'Il colore per "dettaglio veicolo" non è valido';
			$lunghezza_esterna = 'La lunghezza esterna per "dettaglio veicolo" non è valida';
			$larghezza_esterna = 'La larghezza esterna per "dettaglio veicolo" non è valida';
			$massa = 'La massa per "dettaglio veicolo" non è valida';
			$portata = 'La portata per "dettaglio veicolo" non è valida';
			$cilindrata = 'La cilindrata per "dettaglio veicolo" non è valida';
			$potenza = 'La potenza per "dettaglio veicolo" non è valida';
			$numero_assi = 'Il numero di assi per "dettaglio veicolo" non è valido';
			$tipo_asse = 'Il tipo di asse selezionato per "dettaglio veicolo" non è valido';
			$tipo_cambio = 'Il tipo di cambio selezionato per "dettaglio veicolo" non è valido';
			$alimentazione = 'Il tipo di alimentazione selezionato per "dettaglio veicolo" non è valido';
			$destinazione_uso = 'La destinazione d\'uso selezionata per "dettaglio veicolo" non è valida';

			return compact('targa', 'data_immatricolazione', 'id_proprietario', 'id_tipo_veicolo', 'id_tipo_allestimento',
				'id_marca', 'id_modello','id_numero_assi', 'id_tipo_asse', 'id_tipo_cambio', 'id_tipo_alimentazione', 'id_destinazione_uso',
				'colore', 'lunghezza_esterna', 'larghezza_esterna', 'massa', 'portata', 'cilindrata', 'potenza', 'numero_assi', 'tipo_asse',
				'tipo_cambio', 'alimentazione', 'destinazione_uso');
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

			dd(Validator::make($data, $applicableRules, self::validationMessages())->validate());
		}


		/**
		 * Search for vehicles based on a given search term.
		 */
		public static function search($search, $searchField = false)
		{
			return self::query()
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
//					$scopeSearch= DettaglioVeicolo::scopeSearch($query, $search, $searchField);
//					if($scopeSearch==null){
					if($searchField=='id'||$searchField=='id_veicolo') {
						$query->where('dettaglio_veicolo.id', '=', $search);
					} else {
						$query->where('dettaglio_veicolo.id', '=', $search)
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
//					}
//					else {
//						$query=$scopeSearch;
//					}
				})->get([
					'dettaglio_veicolo.id',
					'dettaglio_veicolo.colore',
					'dettaglio_veicolo.massa',
					'dettaglio_veicolo.portata',
					'dettaglio_veicolo.cilindrata',
					'dettaglio_veicolo.potenza',
					'dettaglio_veicolo.numero_assi',
					'societa.nome as societa_nome',
					'tipo_veicolo.nome as tipo_veicolo_nome',
					'tipo_allestimento.nome as tipo_allestimento_nome',
					'marca.nome as marca_nome',
					'modello.nome as modello_nome',
					'destinazione_uso.nome as destinazione_uso_nome',
					'tipo_cambio.nome as tipo_cambio_nome',
					'tipo_asse.nome as tipo_asse_nome',
					'tipo_alimentazione.nome as tipo_alimentazione_nome',
					'targa.targa as targa'
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
				case 'nomesocieta':
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

			$query = DettaglioVeicolo::select([
				'dettaglio_veicolo.id',
				'dettaglio_veicolo.id as id_veicolo',
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
				->leftJoin('targa', 'dettaglio_veicolo.id', '=', 'targa.id_veicolo')
				->leftJoin('societa', 'dettaglio_veicolo.id_proprietario', '=', 'societa.id')
				->leftJoin('tipo_veicolo', 'dettaglio_veicolo.id_tipo_veicolo', '=', 'tipo_veicolo.id')
				->leftJoin('tipo_allestimento', 'dettaglio_veicolo.id_tipo_allestimento', '=', 'tipo_allestimento.id')
				->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
				->leftJoin('modello', 'dettaglio_veicolo.id_modello', '=', 'modello.id')
				->leftJoin('tipo_asse', 'dettaglio_veicolo.tipo_asse', '=', 'tipo_asse.id')
				->leftJoin('tipo_cambio', 'dettaglio_veicolo.tipo_cambio', '=', 'tipo_cambio.id')
				->leftJoin('destinazione_uso', 'dettaglio_veicolo.destinazione_uso', '=', 'destinazione_uso.id')
				->where(function($query) use ($search) {
					$query->where('dettaglio_veicolo.id', '=', "{$search}")
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

		public static function indexOLD($search=null,$order='livello',$page=1,$slice=true): LengthAwarePaginator
		{
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

			if(array_key_exists(2,$order,)) {
				if(strtolower($order[2])=='desc') {
					$orderDirection='DESC';
				} else {
					$orderDirection='ASC';
				}
			}
			else if(array_key_exists(1,$order,)&&strtolower($order[1])=='desc') {
				$orderDirection='DESC';
			} else {
				$orderDirection='ASC';
			}

			$query = DB::table(Veicolo::getTableName())
				->leftJoin(Marca::getTableName(), Veicolo::getTableName() . '.id_marca', '=', Marca::getTableName() . '.id')
				->leftJoin(Modello::getTableName(), function ($join) {
					$join->on(Veicolo::getTableName() . '.id_modello', '=', Modello::getTableName() . '.id')
						->on(Modello::getTableName() . '.id_marca', '=', Marca::getTableName() . '.id');
				})
				->leftJoin(Targa::getTableName(), Targa::getTableName() . '.id_veicolo', '=', Veicolo::getTableName() . '.id')
				->select([
					Marca::getTableName() . '.id as id_marca',
					Marca::getTableName() . '.nome as marca',
					Modello::getTableName() . '.id as id_modello',
					Modello::getTableName() . '.nome as modello',
					Veicolo::getTableName() . '.id as id_veicolo'
				]);

			if ($search !== null) {
				$query->where(function ($query) use ($search) {
					$query->where(Targa::getTableName() . '.targa', 'LIKE', '%' . $search . '%')
						->orWhere(Marca::getTableName() . '.nome', 'LIKE', '%' . $search . '%')
						->orWhere(Modello::getTableName() . '.nome', 'LIKE', '%' . $search . '%');
				});
			}

			//$query->offset(($page - 1) * AlertBase::$itemsPerPage)->limit(AlertBase::$itemsPerPage);

			if ($orderBy!=='id') {
				$result = $query->orderBy($orderBy, $orderDirection)->get();
			} else {
				$result = $query->orderBy(Veicolo::getTableName() . '.id', 'ASC')->get();
			}


			if ($orderBy=='id') {
				if($orderDirection=='DESC') {
					$result=($result->sortByDesc('id'));
				} else {
					$result=($result->sortBy('id'));
				}
			}

			// Manually slice the results for pagination
			$offset = ($page - 1) * AlertBase::$itemsPerPage;
			if($slice) {
				$itemsForCurrentPage = $result->slice($offset, AlertBase::$itemsPerPage);
			} else {
				$itemsForCurrentPage = $result;
			}

			return new LengthAwarePaginator(
				$itemsForCurrentPage,
				$result->count(),
				AlertBase::$itemsPerPage,
				$page,
				['path' => LengthAwarePaginator::resolveCurrentPath()]
			);
		}

		// Relationships
		public static function findOrFail($id)
		{

			$query = DettaglioVeicolo::select([
				'dettaglio_veicolo.id',
				'dettaglio_veicolo.id as id_veicolo',
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
				->leftJoin('targa', 'dettaglio_veicolo.id', '=', 'targa.id_veicolo')
				->leftJoin('societa', 'dettaglio_veicolo.id_proprietario', '=', 'societa.id')
				->leftJoin('tipo_veicolo', 'dettaglio_veicolo.id_tipo_veicolo', '=', 'tipo_veicolo.id')
				->leftJoin('tipo_allestimento', 'dettaglio_veicolo.id_tipo_allestimento', '=', 'tipo_allestimento.id')
				->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
				->leftJoin('modello', 'dettaglio_veicolo.id_modello', '=', 'modello.id')
				->leftJoin('tipo_asse', 'dettaglio_veicolo.tipo_asse', '=', 'tipo_asse.id')
				->leftJoin('tipo_cambio', 'dettaglio_veicolo.tipo_cambio', '=', 'tipo_cambio.id')
				->leftJoin('destinazione_uso', 'dettaglio_veicolo.destinazione_uso', '=', 'destinazione_uso.id')
				->where('dettaglio_veicolo.id', '=', "{$id}");

			$result = $query->get();

			return $result;
		}

		public function societa()
		{
			return $this->belongsTo(Societa::class, 'id_proprietario');
		}

		public function tipo_veicolo()
		{
			return $this->belongsTo(TipoVeicolo::class, 'id_tipo_veicolo');
		}

		public function tipo_allestimento()
		{
			return $this->belongsTo(TipoAllestimento::class, 'id_tipo_allestimento');
		}

		public function marca()
		{
			return $this->belongsTo(Marca::class, 'id_marca');
		}

		public function modello()
		{
			return $this->belongsTo(Modello::class, 'id_modello');
		}

		public function tipo_asse()
		{
			return $this->belongsTo(TipoAsse::class, 'tipo_asse');
		}

		public function tipo_cambio()
		{
			return $this->belongsTo(TipoCambio::class, 'tipo_cambio');
		}

		public function destinazione_uso()
		{
			return $this->belongsTo(DestinazioneUso::class, 'destinazione_uso');
		}

		public function bollo()
		{
			return $this->hasMany(Bollo::class, 'id_veicolo');
		}

		public function targa()
		{
			return $this->hasMany(Targa::class, 'id_veicolo');
		}

		public function telaio()
		{
			return $this->hasMany(Telaio::class, 'id_veicolo');
		}

		public function scopeWithCommonRelationshipsOLD($query, $search)
		{
			$query->orWhere('colore', 'LIKE', "%{$search}%")
				->orWhere('massa', 'LIKE', "%{$search}%")
				->orWhere('portata', 'LIKE', "%{$search}%")
				->orWhere('cilindrata', 'LIKE', "%{$search}%")
				->orWhere('potenza', 'LIKE', "%{$search}%")
				->orWhere('numero_assi', 'LIKE', "%{$search}%")
				->with([
					'societa' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'societa'
					},
					'tipo_veicolo' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'tipo_veicolo'
					},
					'tipo_allestimento' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'tipo_allestimento'
					},
					'marca' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'marca'
					},
					'modello' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'modello'
					},
					'tipo_asse' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'tipo_asse'
					},
					'tipo_cambio' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'tipo_cambio'
					},
					'destinazione_uso' => function ($query) use ($search) {
						$query->orWhere('nome', $search); // Condition for 'destinazione_uso'
					},
					'targa' => function ($query) use ($search) {
						$query->orWhere('targa', $search); // Condition for 'targa'
					},
					'telaio' => function ($query) use ($search) {
						$query->orWhere('telaio', $search); // Condition for 'telaio'
					}
				]);
				//var_dump($query->toSql());
			return $query;
		}

		public function scopeWithCommonRelationships($query, $search)
		{
			// Apply conditions on the DettaglioVeicolo fields
			$query->where(function ($q) use ($search) {
				$q->orWhere('colore', 'LIKE', "%{$search}%")
					->orWhere('massa', 'LIKE', "%{$search}%")
					->orWhere('portata', 'LIKE', "%{$search}%")
					->orWhere('cilindrata', 'LIKE', "%{$search}%")
					->orWhere('potenza', 'LIKE', "%{$search}%")
					->orWhere('numero_assi', 'LIKE', "%{$search}%");
			});

			$relationships = [
				'societa' => 'nome',
				'tipo_veicolo' => 'nome',
				'tipo_allestimento' => 'nome',
				'marca' => 'nome',
				'modello' => 'nome',
				'tipo_asse' => 'nome',
				'tipo_cambio' => 'nome',
				'destinazione_uso' => 'nome',
				'targa' => 'targa',
				'telaio' => 'telaio'
			];

			foreach ($relationships as $relation => $field) {
				$query->orWhereHas($relation, function ($q) use ($search, $field) {
					$q->where($field, 'LIKE', "%{$search}%");
				});
			}

			return $query;
		}

		public static function scopeSearch($query, $search, $searchField = false)
		{
			if (!$search) {
				return $query;
			}

			if ($searchField) {
				// Search only in the specified field.
				return $query->where($searchField, 'LIKE', "%{$search}%");
			} else {
				// Search in all searchable fields.
				return $query->where(function ($q) use ($search) {
					$q->where('dettaglio_veicolo.colore', 'LIKE', "%{$search}%")
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
						->orWhere('dettaglio_veicolo.note_acquisto', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.prezzo', 'LIKE', "%{$search}%")
						->orWhere('dettaglio_veicolo.controparte_vendita', 'LIKE', "%{$search}%")
						->orWhere('targa.targa', 'LIKE', "%{$search}%");
				});
			}
		}

		public static function create ($validatedData): DettaglioVeicolo
		{
			if(is_array($validatedData)&&array_key_exists('targa',$validatedData)) {
				$validatedData['targa']=strtoupper(str_replace(' ', '', $validatedData['targa']));
			}
			$dettaglioVeicolo = new DettaglioVeicolo();
			$dettaglioVeicolo->fill($validatedData);
			$dettaglioVeicolo->save();

			return $dettaglioVeicolo;
		}

	}


















