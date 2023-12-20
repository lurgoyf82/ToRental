<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class AssegnamentoGps extends AlertBase
	{
		use HasFactory;

		protected $table = 'assegnamento_gps';
		public static string $tableName = 'assegnamento_gps';
		protected $fillable = ['id_veicolo', 'id_gps', 'assegnato_da', 'assegnato_a'];

		public function veicolo()
		{
			return $this->belongsTo(Veicolo::class, 'id_veicolo');
		}

		public function gps()
		{
			return $this->belongsTo(Gps::class, 'id_gps');
		}

		public static function validationRules(): array
		{
			$id_veicolo = 'required|exists:dettaglio_veicolo,id';
			$id_gps = 'required|exists:gps,id';
			$assegnato_da = 'required|date_format:Y-m-d';
			$assegnato_a = 'nullable|date_format:Y-m-d|after_or_equal:assegnato_da';

			return compact('id_veicolo', 'id_gps', 'assegnato_da', 'assegnato_a');
		}

		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato per "assegnamento_gps" non è valido';
			$id_gps = 'Il GPS selezionato per "assegnamento_gps" non è valido';
			$assegnato_da = 'La data di assegnazione per "assegnamento_gps" non è valida';
			$assegnato_a = 'La data di fine assegnazione per "assegnamento_gps" non è valida o è precedente alla data di inizio assegnazione';

			return compact('id_veicolo', 'id_gps', 'assegnato_da', 'assegnato_a');
		}

		public static function search($search, $searchField = false)
		{
			$query = self::query()
				->from('assegnamento_gps')
				->leftJoin('dettaglio_veicolo', 'dettaglio_veicolo.id', '=', 'assegnamento_gps.id_veicolo')
				->leftJoin('gps', 'gps.id', '=', 'assegnamento_gps.id_gps');
			$query = self::commonJoins($query)->where(function ($query) use ($search, $searchField) {
				$scopeSearch = self::scopeSearch($query, $search, $searchField);
				if ($scopeSearch == null) {
					// General search across multiple fields
					$query->where('assegnamento_gps.id', '=', $search)
						->orWhere('gps.numero_imei', 'LIKE', "%{$search}%")
						->orWhere('gps.seriale', 'LIKE', "%{$search}%")
						->orWhere('gps.modello', 'LIKE', "%{$search}%")
						->orWhere('gps.costruttore', 'LIKE', "%{$search}%")
						->orWhere('gps.stato', 'LIKE', "%{$search}%");
					$query=self::commonWheres($query,$search);
				} else {
					$query = $scopeSearch;
				}
			});

			$results = $query->get(array_merge([
				'assegnamento_gps.id',
				'assegnamento_gps.id_veicolo',
				'assegnamento_gps.id_gps',
				'assegnamento_gps.assegnato_da',
				'assegnamento_gps.assegnato_a',
				'gps.numero_imei',
				'gps.seriale',
				'gps.modello',
				'gps.costruttore',
				'gps.stato'], self::commonSelect()));

			return $results;
		}
	}
