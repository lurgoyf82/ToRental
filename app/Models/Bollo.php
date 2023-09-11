<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bollo extends Model
{
    use HasFactory;

    protected $table = 'bollo';
    protected $fillable = ['id_veicolo','anno','data_pagamento','inizio_validita','fine_validita','importo'];

    public static function getExpiringScadenzeBolli($search=null): \Illuminate\Support\Collection
    {
        if($search!=null) {
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
}


/*
SELECT
    bollo.id AS id,
    DATE_FORMAT(bollo.inizio_validita, '%d-%m-%Y') AS inizio_validita,
    DATE_FORMAT(bollo.fine_validita, '%d-%m-%Y') AS fine_validita,
    marca.id AS id_marca,
    marca.nome AS marca,
    modello.id AS id_modello,
    modello.nome AS modello,
    dettaglio_veicolo.id AS id_veicolo,
    DATEDIFF(bollo.fine_validita, NOW()) AS livello
FROM
dettaglio_veicolo
LEFT JOIN (
        SELECT
            id_veicolo,
            MAX(fine_validita) AS latest_fine_validita
        FROM
            bollo
        WHERE
            inizio_validita <= NOW()
        GROUP BY
            id_veicolo
    ) AS latest_revision ON latest_revision.id_veicolo= dettaglio_veicolo.id
LEFT JOIN bollo ON bollo.id_veicolo = dettaglio_veicolo.id AND bollo.fine_validita = latest_revision.latest_fine_validita
LEFT JOIN marca ON dettaglio_veicolo.id_marca = marca.id
LEFT JOIN modello ON dettaglio_veicolo.id_modello = modello.id AND modello.id_marca = marca.id
LEFT JOIN targa ON targa.id_veicolo = dettaglio_veicolo.id
WHERE
    targa.targa LIKE CONCAT('%', $search , '%')
ORDER BY
    livello ASC,
    dettaglio_veicolo.id ASC



$query = DB::table('dettaglio_veicolo')
    ->leftJoinSub(
        DB::table('bollo')
            ->select('id_veicolo', DB::raw('MAX(fine_validita) AS latest_fine_validita'))
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

 * */
