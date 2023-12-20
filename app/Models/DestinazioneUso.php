<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class DestinazioneUso extends BaseModel
	{
		use HasFactory;

		protected $table = 'destinazione_uso';
		protected $fillable = ['nome'];


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
					$scopeSearch=self::scopeSearch($query,$search,$searchField);
					if($scopeSearch==null){
						// Search across multiple fields
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
					else {
						$query=$scopeSearch;
					}
				})
				->get([
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
	}
