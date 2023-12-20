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
		static function search($search, $searchField = false)
		{
			return self::query()
				->from('bollo')
				->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', 'bollo.id_veicolo')
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
						$query->where('bollo.id', '=', $search)
						->orWhere('dettaglio_veicolo.id', '=', $search);
					} else {
						// Search across multiple fields
						$query->where('bollo.id', '=', $search)
							->orWhere('bollo.anno', 'LIKE', "%{$search}%")
							->orWhere('bollo.data_pagamento', 'LIKE', "%{$search}%")
							->orWhere('bollo.inizio_validita', 'LIKE', "%{$search}%")
							->orWhere('bollo.fine_validita', 'LIKE', "%{$search}%")
							->orWhere('bollo.importo', 'LIKE', "%{$search}%")
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
					'bollo.id as id',
					'bollo.anno as bollo_anno',
					'bollo.data_pagamento as bollo_data_pagamento',
					'bollo.inizio_validita as bollo_inizio_validita',
					'bollo.fine_validita as bollo_fine_validita',
					'bollo.importo as bollo_importo',
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
