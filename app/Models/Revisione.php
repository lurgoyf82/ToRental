<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;

	class Revisione extends AlertBase
	{
		use HasFactory;

		protected $table = 'revisione';
		public static string $tableName = 'revisione';
		protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];

		public static function cancellami($search=null): \Illuminate\Pagination\LengthAwarePaginator
		{
			return Revisione::getAggregatedAlerts($search);
		}
		public static function validationRules(): array
		{
			$id_veicolo = 'required|exists:dettaglio_veicolo,id';
			$anno = 'required|date_format:Y';
			$data_pagamento = 'required|date_format:Y-m-d';
			$inizio_validita = 'required|date_format:Y-m-d';
			$fine_validita = 'required|date_format:Y-m-d|after_or_equal:inizio_validita';
			$importo = 'required|numeric|min:0';
			$agenzia = 'required|string|max:255';
			$polizza = 'required|string|max:255';
			$tipo_scadenza = 'required|in:Quadrimestrale,Semestrale,Annuale';

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza');
		}

		public static function validationMessages(): array
		{
			$id_veicolo = 'Il veicolo selezionato per "revisione" non è valido';
			$anno = 'L\'anno specificato per "revisione" non è valido';
			$data_pagamento = 'La data di pagamento per "revisione" non è valida';
			$inizio_validita = 'La data di inizio validità per "revisione" non è valida';
			$fine_validita = 'La data di fine validità per "revisione" non è valida o è precedente alla data di inizio validità';
			$importo = 'L\'importo per "revisione" non è valido o è negativo';
			$agenzia = 'L\'agenzia per "revisione" non è valida';
			$polizza = 'La polizza per "revisione" non è valida';
			$tipo_scadenza = 'Il tipo di scadenza per "revisione" non è valido o non è tra le opzioni consentite';

			return compact('id_veicolo', 'anno', 'data_pagamento', 'inizio_validita', 'fine_validita', 'importo', 'agenzia', 'polizza', 'tipo_scadenza');
		}


	}
