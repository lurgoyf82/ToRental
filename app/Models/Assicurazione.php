<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Assicurazione extends AlertBase
{
	use HasFactory;

	protected $table = 'assicurazione';
	public static string $tableName = 'assicurazione';
	protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo','agenzia','polizza','tipo_scadenza'];

	public static function getExpiringPolizzeAssicurative($search=null): \Illuminate\Support\Collection
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
		$id_veicolo = 'Il veicolo selezionato per "assicurazione" non è valido';
		$targa = 'La targa per "assicurazione" non è valida';
		$anno = 'L\'anno specificato per "assicurazione" non è valido';
		$data_pagamento = 'La data di pagamento per "assicurazione" non è valida';
		$inizio_validita = 'La data di inizio validità per "assicurazione" non è valida';
		$fine_validita = 'La data di fine validità per "assicurazione" non è valida o è precedente alla data di inizio validità';
		$importo = 'L\'importo per "assicurazione" non è valido o è negativo';
		$agenzia = 'L\'agenzia per "assicurazione" non è valida';
		$polizza = 'La polizza per "assicurazione" non è valida';
		$tipo_scadenza = 'Il tipo di scadenza per "assicurazione" non è valido o non è tra le opzioni consentite';

		return compact('id_veicolo', 'targa', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza');
	}
//
//	static function search($search, $searchField = false, $searchFieldVeicolo = false)
//	{
//		return self::query()
//			->from('assicurazione')
//			->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', 'assicurazione.id_veicolo')
//			->leftJoin('societa', 'societa.id', '=', 'dettaglio_veicolo.id_proprietario')
//			->leftJoin('tipo_veicolo', 'tipo_veicolo.id', '=', 'dettaglio_veicolo.id_tipo_veicolo')
//			->leftJoin('tipo_allestimento', 'tipo_allestimento.id', '=', 'dettaglio_veicolo.id_tipo_allestimento')
//			->leftJoin('marca', 'marca.id', '=', 'dettaglio_veicolo.id_marca')
//			->leftJoin('modello', 'modello.id', '=', 'dettaglio_veicolo.id_modello')
//			->leftJoin('tipo_asse', 'tipo_asse.id', '=', 'dettaglio_veicolo.tipo_asse')
//			->leftJoin('tipo_cambio', 'tipo_cambio.id', '=', 'dettaglio_veicolo.tipo_cambio')
//			->leftJoin('destinazione_uso', 'destinazione_uso.id', '=', 'dettaglio_veicolo.destinazione_uso')
//			->leftJoin('tipo_alimentazione', 'tipo_alimentazione.id', '=', 'dettaglio_veicolo.alimentazione')
//			->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
//			->where(function ($query) use ($search, $searchField, $searchFieldVeicolo) {
//				if ($searchField) {
//					// Only search by ID
//					$query->where('assicurazione.id', '=', $search);
//				} else
//					if ($searchFieldVeicolo) {
//						// Only search by ID
//						$query->where('dettaglio_veicolo.id', '=', $search);
//					} else {
//						// Search across multiple fields
//						$query->where('assicurazione.id', '=', $search)
//							->orWhere('assicurazione.targa', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.anno', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.data_pagamento', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.inizio_validita', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.fine_validita', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.importo', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.agenzia', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.polizza', 'LIKE', "%{$search}%")
//							->orWhere('assicurazione.tipo_scadenza', 'LIKE', "%{$search}%")
//							->orWhere('dettaglio_veicolo.colore', 'LIKE', "%{$search}%")
//							->orWhere('dettaglio_veicolo.massa', 'LIKE', "%{$search}%")
//							->orWhere('dettaglio_veicolo.portata', 'LIKE', "%{$search}%")
//							->orWhere('dettaglio_veicolo.cilindrata', 'LIKE', "%{$search}%")
//							->orWhere('dettaglio_veicolo.potenza', 'LIKE', "%{$search}%")
//							->orWhere('dettaglio_veicolo.numero_assi', 'LIKE', "%{$search}%")
//							->orWhere('societa.nome', 'LIKE', "%{$search}%")
//							->orWhere('tipo_veicolo.nome', 'LIKE', "%{$search}%")
//							->orWhere('tipo_allestimento.nome', 'LIKE', "%{$search}%")
//							->orWhere('marca.nome', 'LIKE', "%{$search}%")
//							->orWhere('modello.nome', 'LIKE', "%{$search}%")
//							->orWhere('destinazione_uso.nome', 'LIKE', "%{$search}%")
//							->orWhere('tipo_cambio.nome', 'LIKE', "%{$search}%")
//							->orWhere('tipo_asse.nome', 'LIKE', "%{$search}%")
//							->orWhere('tipo_alimentazione.nome', 'LIKE', "%{$search}%")
//							->orWhere('targa.targa', 'LIKE', "%{$search}%");
//					}
//			})
//			->get([
//				'assicurazione.id as id',
//				'assicurazione.targa',
//				'assicurazione.anno',
//				'assicurazione.data_pagamento',
//				'assicurazione.inizio_validita',
//				'assicurazione.fine_validita',
//				'assicurazione.importo',
//				'assicurazione.agenzia',
//				'assicurazione.polizza',
//				'assicurazione.tipo_scadenza',
//				'dettaglio_veicolo.id as veicolo_id',
//				'dettaglio_veicolo.colore as veicolo_colore',
//				'dettaglio_veicolo.massa as veicolo_massa',
//				'dettaglio_veicolo.portata as veicolo_portata',
//				'dettaglio_veicolo.cilindrata as veicolo_cilindrata',
//				'dettaglio_veicolo.potenza as veicolo_potenza',
//				'dettaglio_veicolo.numero_assi as veicolo_numero_assi',
//				'societa.nome as societa_nome',
//				'tipo_veicolo.nome as tipo_veicolo_nome',
//				'tipo_allestimento.nome as tipo_allestimento_nome',
//				'marca.nome as marca_nome',
//				'modello.nome as modello_nome',
//				'destinazione_uso.nome as destinazione_uso_nome',
//				'tipo_cambio.nome as tipo_cambio_nome',
//				'tipo_asse.nome as tipo_asse_nome',
//				'tipo_alimentazione.nome as tipo_alimentazione_nome',
//				'targa.targa as veicolo_targa'
//			]);
//	}


	public static function search($search, $searchField = false)
	{
		return self::query()
			->from('assicurazione')
			->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', 'assicurazione.id_veicolo')
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
				$scopeSearch=self::scopeSearch($query,$search,$searchField);
				if($scopeSearch==null){
					// General search across multiple fields
					$query->where('assicurazione.id', '=', $search)
						->orWhere('assicurazione.targa', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.anno', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.data_pagamento', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.inizio_validita', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.fine_validita', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.importo', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.agenzia', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.polizza', 'LIKE', "%{$search}%")
						->orWhere('assicurazione.tipo_scadenza', 'LIKE', "%{$search}%")
						->orWhere('gps.numero_imei', 'LIKE', "%{$search}%")
						->orWhere('gps.seriale', 'LIKE', "%{$search}%")
						->orWhere('gps.modello', 'LIKE', "%{$search}%")
						->orWhere('gps.costruttore', 'LIKE', "%{$search}%")
						->orWhere('gps.stato', 'LIKE', "%{$search}%")
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
				else {
					$query=$scopeSearch;
				}
			})
			->get([
				'assicurazione.id as id',
				'assicurazione.targa',
				'assicurazione.anno',
				'assicurazione.data_pagamento',
				'assicurazione.inizio_validita',
				'assicurazione.fine_validita',
				'assicurazione.importo',
				'assicurazione.agenzia',
				'assicurazione.polizza',
				'assicurazione.tipo_scadenza',
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
			->orWhere('assicurazione.targa', '=', $search)
			->orWhere('assicurazione.anno', '=', $search)
			->orWhere('assicurazione.data_pagamento', 'LIKE', "%{$search}%")
			->orWhere('assicurazione.inizio_validita', 'LIKE', "%{$search}%")
			->orWhere('assicurazione.fine_validita', 'LIKE', "%{$search}%")
			->orWhere('assicurazione.importo', 'LIKE', "%{$search}%")
			->orWhere('assicurazione.agenzia', 'LIKE', "%{$search}%")
			->orWhere('assicurazione.polizza', 'LIKE', "%{$search}%")
			->orWhere('assicurazione.tipo_scadenza', 'LIKE', "%{$search}%");

		if ($orderBy!=='id_veicolo') {
			$query = $query->orderBy($orderBy, $orderDirection)->get();
		} else {
			$query = $query->orderBy('assicurazione.id_veicolo', 'ASC')->get();
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
