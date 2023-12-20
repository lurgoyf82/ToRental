<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class BaseModel extends Model
	{
		public static $itemsPerPage = 25;

		public static function getTableName(): string
		{
			return (new static)->table;
		}

		public static function scopeSearch($query, $search, $searchField = false)
		{
			switch ($searchField) {
				case 'id':
					$query->where(self::getTableName() . '.id', '=', $search);
					break;
				case 'id_veicolo':
					$query->where('dettaglio_veicolo.id', '=', $search);
					break;
				case 'targa':
					$query->where('targa.targa', 'LIKE', "%{$search}%");
					break;
				default:
					$query = null;
					break;
			}
			return $query;
		}

		protected static function commonJoins($query)
		{
			return ($query->leftJoin('societa', 'societa.id', '=', 'dettaglio_veicolo.id_proprietario')
				->leftJoin('tipo_veicolo', 'tipo_veicolo.id', '=', 'dettaglio_veicolo.id_tipo_veicolo')
				->leftJoin('tipo_allestimento', 'tipo_allestimento.id', '=', 'dettaglio_veicolo.id_tipo_allestimento')
				->leftJoin('marca', 'marca.id', '=', 'dettaglio_veicolo.id_marca')
				->leftJoin('modello', 'modello.id', '=', 'dettaglio_veicolo.id_modello')
				->leftJoin('tipo_asse', 'tipo_asse.id', '=', 'dettaglio_veicolo.tipo_asse')
				->leftJoin('tipo_cambio', 'tipo_cambio.id', '=', 'dettaglio_veicolo.tipo_cambio')
				->leftJoin('destinazione_uso', 'destinazione_uso.id', '=', 'dettaglio_veicolo.destinazione_uso')
				->leftJoin('tipo_alimentazione', 'tipo_alimentazione.id', '=', 'dettaglio_veicolo.alimentazione')
				->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id'));
		}

		protected static function commonWheres($query, $search)
		{
			return ($query->orWhere('dettaglio_veicolo.id', '=', $search)
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
				->orWhere('targa.targa', 'LIKE', "%{$search}%"));
		}

		protected static function commonSelect()
		{
			return (['dettaglio_veicolo.id as veicolo_id',
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
				'targa.targa as veicolo_targa']);
		}
	}

