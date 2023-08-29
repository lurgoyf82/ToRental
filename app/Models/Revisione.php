<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Revisione extends Model
{
    use HasFactory;

    protected $table = 'revisione';
    protected $fillable = ['veicolo_id','anno','data_pagamento','inizio_validita','fine_validita','importo'];

    public static function getRevisioneAlertList() {
        $query = DB::table('dettaglio_veicolo')
            ->leftJoinSub(
                DB::table('revisione')
                    ->select(DB::raw('veicolo_id, MAX(fine_validita) as latest_fine_validita'))
                    ->where('inizio_validita', '<=', now())
                    ->groupBy('veicolo_id'),
                'latest_revision',
                'latest_revision.veicolo_id',
                '=',
                'dettaglio_veicolo.id'
            )
            ->leftJoin('revisione as r', function ($join) {
                $join->on('r.veicolo_id', '=', 'dettaglio_veicolo.id')
                    ->on('r.fine_validita', '=', 'latest_revision.latest_fine_validita');
            })
            ->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
            ->leftJoin('modello', function ($join) {
                $join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
                    ->on('modello.id_marca', '=', 'marca.id');
            })
            ->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
            ->select([
                'r.inizio_validita',
                'r.fine_validita',
                'marca.nome as marca',
                'modello.nome as modello',
                'dettaglio_veicolo.id',
                'targa.targa',
                DB::raw('DATEDIFF(r.fine_validita, NOW()) as livello')
            ])
            ->orderBy('dettaglio_veicolo.id', 'ASC')
            ->get();

        return $query;
    }
}
