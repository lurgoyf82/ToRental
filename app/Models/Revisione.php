<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Revisione extends Model
{
    use HasFactory;

    protected $table = 'revisione';
    protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];

    public static function getExpiringRevisioniMeccaniche($search=null): \Illuminate\Support\Collection
    {
        if($search!=null) {
            $query = DB::table('dettaglio_veicolo')
                ->leftJoinSub(
                    DB::table('revisione')
                        ->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
                        ->where('inizio_validita', '<=', now())
                        ->groupBy('id_veicolo'),
                    'latest_revision',
                    'latest_revision.id_veicolo',
                    '=',
                    'dettaglio_veicolo.id'
                )
                ->leftJoin('revisione', function ($join) {
                    $join->on('revisione.id_veicolo', '=', 'dettaglio_veicolo.id')
                        ->on('revisione.fine_validita', '=', 'latest_revision.latest_fine_validita');
                })
                ->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
                ->leftJoin('modello', function ($join) {
                    $join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
                        ->on('modello.id_marca', '=', 'marca.id');
                })
                ->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
                ->select([
                    'revisione.id as id',
                    DB::raw("DATE_FORMAT(revisione.inizio_validita, '%d-%m-%Y') as inizio_validita"),
                    DB::raw("DATE_FORMAT(revisione.fine_validita, '%d-%m-%Y') as fine_validita"),
                    'marca.id as id_marca',
                    'marca.nome as marca',
                    'modello.id as id_modello',
                    'modello.nome as modello',
                    'dettaglio_veicolo.id as id_veicolo',
                    DB::raw('DATEDIFF(revisione.fine_validita, NOW()) as livello')
                ])
                ->where('targa.targa', 'LIKE', '%' . $search . '%')
                ->orderBy('livello', 'ASC')
                ->orderBy('dettaglio_veicolo.id', 'ASC')
                ->get();

        } else {

            $query = DB::table('dettaglio_veicolo')
                ->leftJoinSub(
                    DB::table('revisione')
                        ->select(DB::raw('id_veicolo, MAX(fine_validita) as latest_fine_validita'))
                        ->where('inizio_validita', '<=', now())
                        ->groupBy('id_veicolo'),
                    'latest_revision',
                    'latest_revision.id_veicolo',
                    '=',
                    'dettaglio_veicolo.id'
                )
                ->leftJoin('revisione', function ($join) {
                    $join->on('revisione.id_veicolo', '=', 'dettaglio_veicolo.id')
                        ->on('revisione.fine_validita', '=', 'latest_revision.latest_fine_validita');
                })
                ->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
                ->leftJoin('modello', function ($join) {
                    $join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
                        ->on('modello.id_marca', '=', 'marca.id');
                })
                ->select([
                    'revisione.id as id',
                    DB::raw("DATE_FORMAT(revisione.inizio_validita, '%d-%m-%Y') as inizio_validita"),
                    DB::raw("DATE_FORMAT(revisione.fine_validita, '%d-%m-%Y') as fine_validita"),
                    'marca.id as id_marca',
                    'marca.nome as marca',
                    'modello.id as id_modello',
                    'modello.nome as modello',
                    'dettaglio_veicolo.id as id_veicolo',
                    DB::raw('DATEDIFF(revisione.fine_validita, NOW()) as livello')
                ])
                ->orderBy('livello', 'ASC')
                ->orderBy('dettaglio_veicolo.id', 'ASC')
                ->get();
        }

        return $query;
    }
}
