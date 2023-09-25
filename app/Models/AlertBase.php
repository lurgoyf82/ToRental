<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AlertBase extends Model
{
    use HasFactory;
    public static string $tableName = 'assicurazione';  // Default table name
    public function getTable() {
        return (static::$tableName);
    }

    public static function getValidList() {
        return(DB::table(static::$tableName)
            ->select([
                static::$tableName.'.id_veicolo as id_veicolo',
                DB::raw('MAX('.static::$tableName.'.fine_validita) as fine_validita'),
                DB::raw('DATEDIFF(MAX('.static::$tableName.'.fine_validita), NOW()) as days_until_expiration')
            ])
            ->where(static::$tableName.'.fine_validita', '>', now())
            ->where(static::$tableName.'.inizio_validita', '<', now())
            ->groupBy(static::$tableName.'.id_veicolo')
            ->orderBy(static::$tableName.'.id_veicolo', 'ASC')
            ->get()
            ->keyBy('id_veicolo'));
    }

    public static function getExpiredList() {
        $records = DB::table('assicurazione')
            ->select('*')
            ->where('fine_validita', '<=', now())
            ->orderBy('id_veicolo')
            ->orderByDesc('fine_validita')
            ->get();

        $results=$records->groupBy('id_veicolo')->map(function ($group) {
            return $group->first();
        });

        dd($results);
    }
    public static function getStartingNextList() {
        /*
         * SELECT
         * static::$tableName.id_veicolo,
         * MIN(static::$tableName.inizio_validita) as next_start_date,
         * DATEDIFF(MIN(static::$tableName.inizio_validita), NOW()) as days_until_start
         * FROM
         * static::$tableName
         * WHERE
         * static::$tableName.inizio_validita > NOW()
         * GROUP BY
         * static::$tableName.id_veicolo
         * ORDER BY
         * id_veicolo;
         */
        return(DB::table(static::$tableName)
            ->select([
                static::$tableName.'.id_veicolo as id_veicolo',
                DB::raw('MIN('.static::$tableName.'.inizio_validita) as next_start_date'),
                DB::raw('DATEDIFF(MIN('.static::$tableName.'.inizio_validita), NOW()) as days_until_start')
            ])
            ->where(static::$tableName.'.inizio_validita', '>', now())
            ->groupBy(static::$tableName.'.id_veicolo')
            ->orderBy(static::$tableName.'.id_veicolo', 'ASC')
            ->get()
            ->keyBy('id_veicolo'));
    }

    public static function getAlerts($search=null): \Illuminate\Support\Collection
    {

        $start_time = microtime(true);
        $valid=static::getValidList();
        echo "getValidList took ".(microtime(true)-$start_time)." seconds.\n";
        $start_time = microtime(true);
        $expired=static::getExpiredList();
        echo "getExpiredList took ".(microtime(true)-$start_time)." seconds.\n";
        $start_time = microtime(true);
        $startingNext=static::getStartingNextList();
        echo "getStartingNextList took ".(microtime(true)-$start_time)." seconds.\n";
        die();

        $query = DB::table(Veicolo::$tableName)
            ->leftJoin('marca', 'dettaglio_veicolo.id_marca', '=', 'marca.id')
            ->leftJoin('modello', function ($join) {
                $join->on('dettaglio_veicolo.id_modello', '=', 'modello.id')
                    ->on('modello.id_marca', '=', 'marca.id');
            })
            ->leftJoin('targa', 'targa.id_veicolo', '=', 'dettaglio_veicolo.id')
            ->select([
                'marca.id as id_marca',
                'marca.nome as marca',
                'modello.id as id_modello',
                'modello.nome as modello',
                Veicolo::$tableName.'.id as id_veicolo'
            ]);
        if ($search !== null) {
            $query->where('targa.targa', 'LIKE', '%' . $search . '%');
        }

        return $query->orderBy('dettaglio_veicolo.id', 'ASC')->get();
    }
}
