<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Alert extends Model
{
    use HasFactory;

    public static function all($columns = ['*'])
    {
        $query = DB::table('dettaglio_veicolo')
            ->leftJoin('revisione', function ($join) {
                $join->on('revisione.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('revisione.inizio_validita', '<', now())
                    ->where('revisione.fine_validita', '>', now());
            })
            ->leftJoin('bollo', function ($join) {
                $join->on('bollo.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('bollo.inizio_validita', '<', now())
                    ->where('bollo.fine_validita', '>', now());
            })
            ->leftJoin('bombole', function ($join) {
                $join->on('bombole.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('bombole.inizio_validita', '<', now())
                    ->where('bombole.fine_validita', '>', now());
            })
            ->leftJoin('cronotachigrafo', function ($join) {
                $join->on('cronotachigrafo.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('cronotachigrafo.inizio_validita', '<', now())
                    ->where('cronotachigrafo.fine_validita', '>', now());
            })
            ->leftJoin('tachigrafo', function ($join) {
                $join->on('tachigrafo.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('tachigrafo.inizio_validita', '<', now())
                    ->where('tachigrafo.fine_validita', '>', now());
            })
            ->select([
                'revisione.fine_validita as revisione_fine_validita',
                'tachigrafo.fine_validita as tachigrafo_fine_validita',
                'bollo.fine_validita as bollo_fine_validita',
                'bombole.fine_validita as bombole_fine_validita',
                'cronotachigrafo.fine_validita as cronotachigrafo_fine_validita',
                'dettaglio_veicolo.*'
            ])
            ->orderBy('dettaglio_veicolo.id', 'ASC');

        return $query->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }

    //custom all() function


    /*
    public static function all()
    {
        $query = DB::table('dettaglio_veicolo')
            ->leftJoin('revisione', function ($join) {
                $join->on('revisione.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('revisione.inizio_validita', '<', now())
                    ->where('revisione.fine_validita', '>', now());
            })
            ->leftJoin('bollo', function ($join) {
                $join->on('bollo.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('bollo.inizio_validita', '<', now())
                    ->where('bollo.fine_validita', '>', now());
            })
            ->leftJoin('bombole', function ($join) {
                $join->on('bombole.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('bombole.inizio_validita', '<', now())
                    ->where('bombole.fine_validita', '>', now());
            })
            ->leftJoin('cronotachigrafo', function ($join) {
                $join->on('cronotachigrafo.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('cronotachigrafo.inizio_validita', '<', now())
                    ->where('cronotachigrafo.fine_validita', '>', now());
            })
            ->leftJoin('tachigrafo', function ($join) {
                $join->on('tachigrafo.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->where('tachigrafo.inizio_validita', '<', now())
                    ->where('tachigrafo.fine_validita', '>', now());
            })
            ->select([
                'revisione.fine_validita as revisione_fine_validita',
                'tachigrafo.fine_validita as tachigrafo_fine_validita',
                'bollo.fine_validita as bollo_fine_validita',
                'bombole.fine_validita as bombole_fine_validita',
                'cronotachigrafo.fine_validita as cronotachigrafo_fine_validita',
                'dettaglio_veicolo.*'
            ])
            ->orderBy('dettaglio_veicolo.id', 'ASC')
            ->get();


    }
    */
}
