<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Validator;

	class Tagliando extends AlertBase
	{
		use HasFactory;

		protected $table = 'tagliando';
		public static string $tableName = 'tagliando';
		protected $fillable = ['id_veicolo', 'targa', 'anno', 'data_pagamento', 'inizio_validita',
			'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza'];

		public static function getExpiringTagliando($search = null): \Illuminate\Support\Collection
		{
			if ($search != null) {
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
			$id_veicolo = 'required|exists:dettaglio_veicolo,id';
			$targa = 'required|string|max:255';
			$anno = 'required|date_format:Y';
			$data_pagamento = 'required|date_format:Y-m-d';
			$inizio_validita = 'required|date_format:Y-m-d';
			$fine_validita = 'required|date_format:Y-m-d|after_or_equal:inizio_validita';
			$importo = 'required|numeric|min:0';
			$agenzia = 'required|string|max:255';
			$polizza = 'required|string|max:255';
			$tipo_scadenza = 'required|in:Quadrimestrale,Semestrale,Annuale';

			return compact('id_veicolo', 'targa', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza');
		}

		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato per "tagliando" non è valido';
			$targa = 'La targa per "tagliando" non è valida';
			$anno = 'L\'anno specificato per "tagliando" non è valido';
			$data_pagamento = 'La data di pagamento per "tagliando" non è valida';
			$inizio_validita = 'La data di inizio validità per "tagliando" non è valida';
			$fine_validita = 'La data di fine validità per "tagliando" non è valida o è precedente alla data di inizio validità';
			$importo = 'L\'importo per "tagliando" non è valido o è negativo';
			$agenzia = 'L\'agenzia per "tagliando" non è valida';
			$polizza = 'La polizza per "tagliando" non è valida';
			$tipo_scadenza = 'Il tipo di scadenza per "tagliando" non è valido o non è tra le opzioni consentite';

			return compact('id_veicolo', 'targa', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza');
		}

		/**
		 * Search for vehicles based on a given search term.
		 */

		static function search($search, $exactId = false)
		{
			return self::query()
				->from('tagliando')
				->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', 'tagliando.id_veicolo')
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
				->where(function ($query) use ($search, $exactId) {
					if ($exactId) {
						// Only search by ID
						$query->where('tagliando.id', '=', $search);
					} else {
						// Search across multiple fields
						$query->where('tagliando.id', '=', $search)
							->orWhere('tagliando.anno', 'LIKE', "%{$search}%")
							->orWhere('tagliando.data_pagamento', 'LIKE', "%{$search}%")
							->orWhere('tagliando.inizio_validita', 'LIKE', "%{$search}%")
							->orWhere('tagliando.fine_validita', 'LIKE', "%{$search}%")
							->orWhere('tagliando.importo', 'LIKE', "%{$search}%")
							->orWhere('tagliando.agenzia', 'LIKE', "%{$search}%")
							->orWhere('tagliando.polizza', 'LIKE', "%{$search}%")
							->orWhere('tagliando.tipo_scadenza', 'LIKE', "%{$search}%")
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
					'tagliando.id as id',
					'tagliando.anno as tagliando_anno',
					'tagliando.data_pagamento as tagliando_data_pagamento',
					'tagliando.inizio_validita as tagliando_inizio_validita',
					'tagliando.fine_validita as tagliando_fine_validita',
					'tagliando.importo as tagliando_importo',
					'tagliando.agenzia as tagliando_agenzia',
					'tagliando.polizza as tagliando_polizza',
					'tagliando.tipo_scadenza as tagliando_tipo_scadenza',
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
	}
