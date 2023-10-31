<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Alert extends BaseModel
{
	use HasFactory;

	//Parameterized values for alert levels
	public static int $firstThreshold = 7;
	public static int $secondThreshold = 15;
	public static int $thirdThreshold = 30;

	public static function getAggregatedAlerts($columns = ['*']) {
		$query = DB::table('dettaglio_veicolo')
			->leftJoin('revisione', function ($join) {
				$join->on('revisione.id_veicolo', '=', 'dettaglio_veicolo.id')
					->where('revisione.inizio_validita', '<', now())
					->where('revisione.fine_validita', '>', now());
			})
			->leftJoin('bollo', function ($join) {
				$join->on('bollo.id_veicolo', '=', 'dettaglio_veicolo.id')
					->where('bollo.inizio_validita', '<', now())
					->where('bollo.fine_validita', '>', now());
			})
			->leftJoin('bombole', function ($join) {
				$join->on('bombole.id_veicolo', '=', 'dettaglio_veicolo.id')
					->where('bombole.inizio_validita', '<', now())
					->where('bombole.fine_validita', '>', now());
			})
			->leftJoin('atp', function ($join) {
				$join->on('atp.id_veicolo', '=', 'dettaglio_veicolo.id')
					->where('atp.inizio_validita', '<', now())
					->where('atp.fine_validita', '>', now());
			})
			->leftJoin('noleggio', function ($join) {
				$join->on('noleggio.id_veicolo', '=', 'dettaglio_veicolo.id')
					->where('noleggio.inizio_validita', '<', now())
					->where('noleggio.fine_validita', '>', now());
			})
			->leftJoin('assicurazione', function ($join) {
				$join->on('assicurazione.id_veicolo', '=', 'dettaglio_veicolo.id')
					->where('assicurazione.inizio_validita', '<', now())
					->where('assicurazione.fine_validita', '>', now());
			})
			->leftJoin('tachigrafo', function ($join) {
				$join->on('tachigrafo.id_veicolo', '=', 'dettaglio_veicolo.id')
					->where('tachigrafo.inizio_validita', '<', now())
					->where('tachigrafo.fine_validita', '>', now());
			})
			->select([
				DB::raw("DATE_FORMAT(revisione.fine_validita, '%d-%m-%Y') as revisione_fine_validita"),
				DB::raw("DATE_FORMAT(tachigrafo.fine_validita, '%d-%m-%Y') as tachigrafo_fine_validita"),
				DB::raw("DATE_FORMAT(bollo.fine_validita, '%d-%m-%Y') as bollo_fine_validita"),
				DB::raw("DATE_FORMAT(bombole.fine_validita, '%d-%m-%Y') as bombole_fine_validita"),
				DB::raw("DATE_FORMAT(atp.fine_validita, '%d-%m-%Y') as atp_fine_validita"),
				DB::raw("DATE_FORMAT(noleggio.fine_validita, '%d-%m-%Y') as noleggio_fine_validita"),
				DB::raw("DATE_FORMAT(assicurazione.fine_validita, '%d-%m-%Y') as assicurazione_fine_validita"),
				//'revisione.fine_validita as revisione_fine_validita_a',
				//'tachigrafo.fine_validita as tachigrafo_fine_validita_a',
				//'bollo.fine_validita as bollo_fine_validita_a',
				//'bombole.fine_validita as bombole_fine_validita_a',
				//'cronotachigrafo.fine_validita as cronotachigrafo_fine_validita_a',
				'dettaglio_veicolo.*'
			])
			->selectRaw("
				CASE
					WHEN revisione.fine_validita IS NULL THEN 4
					WHEN DATEDIFF(revisione.fine_validita, NOW()) BETWEEN 0 AND ? THEN 3
					WHEN DATEDIFF(revisione.fine_validita, NOW()) BETWEEN ? AND ? THEN 2
					WHEN DATEDIFF(revisione.fine_validita, NOW()) BETWEEN ? AND ? THEN 1
					WHEN DATEDIFF(revisione.fine_validita, NOW()) >= ? THEN 0
					ELSE 4
				END AS revisione_alert_level,
				CASE
					WHEN bollo.fine_validita IS NULL THEN 4
					WHEN DATEDIFF(bollo.fine_validita, NOW()) BETWEEN 0 AND ? THEN 3
					WHEN DATEDIFF(bollo.fine_validita, NOW()) BETWEEN ? AND ? THEN 2
					WHEN DATEDIFF(bollo.fine_validita, NOW()) BETWEEN ? AND ? THEN 1
					WHEN DATEDIFF(bollo.fine_validita, NOW()) >= ? THEN 0
					ELSE 4
				END AS bollo_alert_level,
				CASE
					WHEN bombole.fine_validita IS NULL THEN 4
					WHEN DATEDIFF(bombole.fine_validita, NOW()) BETWEEN 0 AND ? THEN 3
					WHEN DATEDIFF(bombole.fine_validita, NOW()) BETWEEN ? AND ? THEN 2
					WHEN DATEDIFF(bombole.fine_validita, NOW()) BETWEEN ? AND ? THEN 1
					WHEN DATEDIFF(bombole.fine_validita, NOW()) >= ? THEN 0
					ELSE 4
				END AS bombole_alert_level,
				CASE
					WHEN atp.fine_validita IS NULL THEN 4
					WHEN DATEDIFF(atp.fine_validita, NOW()) BETWEEN 0 AND ? THEN 3
					WHEN DATEDIFF(atp.fine_validita, NOW()) BETWEEN ? AND ? THEN 2
					WHEN DATEDIFF(atp.fine_validita, NOW()) BETWEEN ? AND ? THEN 1
					WHEN DATEDIFF(atp.fine_validita, NOW()) >= ? THEN 0
					ELSE 4
				END AS atp_alert_level,
				CASE
					WHEN noleggio.fine_validita IS NULL THEN 4
					WHEN DATEDIFF(noleggio.fine_validita, NOW()) BETWEEN 0 AND ? THEN 3
					WHEN DATEDIFF(noleggio.fine_validita, NOW()) BETWEEN ? AND ? THEN 2
					WHEN DATEDIFF(noleggio.fine_validita, NOW()) BETWEEN ? AND ? THEN 1
					WHEN DATEDIFF(noleggio.fine_validita, NOW()) >= ? THEN 0
					ELSE 4
				END AS noleggio_alert_level,
				CASE
					WHEN assicurazione.fine_validita IS NULL THEN 4
					WHEN DATEDIFF(assicurazione.fine_validita, NOW()) BETWEEN 0 AND ? THEN 3
					WHEN DATEDIFF(assicurazione.fine_validita, NOW()) BETWEEN ? AND ? THEN 2
					WHEN DATEDIFF(assicurazione.fine_validita, NOW()) BETWEEN ? AND ? THEN 1
					WHEN DATEDIFF(assicurazione.fine_validita, NOW()) >= ? THEN 0
					ELSE 4
				END AS assicurazione_alert_level,
				CASE
					WHEN tachigrafo.fine_validita IS NULL THEN 4
					WHEN DATEDIFF(tachigrafo.fine_validita, NOW()) BETWEEN 0 AND ? THEN 3
					WHEN DATEDIFF(tachigrafo.fine_validita, NOW()) BETWEEN ? AND ? THEN 2
					WHEN DATEDIFF(tachigrafo.fine_validita, NOW()) BETWEEN ? AND ? THEN 1
					WHEN DATEDIFF(tachigrafo.fine_validita, NOW()) >= ? THEN 0
					ELSE 4
				END AS tachigrafo_alert_level", [
				Alert::$firstThreshold, Alert::$firstThreshold + 1, Alert::$secondThreshold, Alert::$secondThreshold + 1, Alert::$thirdThreshold, Alert::$thirdThreshold,
				Alert::$firstThreshold, Alert::$firstThreshold + 1, Alert::$secondThreshold, Alert::$secondThreshold + 1, Alert::$thirdThreshold, Alert::$thirdThreshold,
				Alert::$firstThreshold, Alert::$firstThreshold + 1, Alert::$secondThreshold, Alert::$secondThreshold + 1, Alert::$thirdThreshold, Alert::$thirdThreshold,
				Alert::$firstThreshold, Alert::$firstThreshold + 1, Alert::$secondThreshold, Alert::$secondThreshold + 1, Alert::$thirdThreshold, Alert::$thirdThreshold,
				Alert::$firstThreshold, Alert::$firstThreshold + 1, Alert::$secondThreshold, Alert::$secondThreshold + 1, Alert::$thirdThreshold, Alert::$thirdThreshold,
				Alert::$firstThreshold, Alert::$firstThreshold + 1, Alert::$secondThreshold, Alert::$secondThreshold + 1, Alert::$thirdThreshold, Alert::$thirdThreshold,
				Alert::$firstThreshold, Alert::$firstThreshold + 1, Alert::$secondThreshold, Alert::$secondThreshold + 1, Alert::$thirdThreshold, Alert::$thirdThreshold
			])
			->orderBy('dettaglio_veicolo.id', 'ASC');

		return $query->get(
			is_array($columns) ? $columns : func_get_args()
		);
	}

}
