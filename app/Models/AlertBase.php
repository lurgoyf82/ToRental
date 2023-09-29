<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AlertBase extends Model
{
    use HasFactory;

    protected $table = 'assicurazione'; // Default table

    public static function getCurrentValidList ($calculateNewCachedData = true) : mixed
    {
        $table=(new static)->getTable();
        $cacheKey = 'valid_' . $table . '_list';

        if ($calculateNewCachedData || !Cache::has($cacheKey)) {
            $results = DB::table($table)
                ->select([
                    'id as valid_id',
                    'id_veicolo',
                    'inizio_validita as valid_inizio_validita',
                    'fine_validita as valid_fine_validita'
                ])
                ->where('fine_validita', '>', now())
                ->where('inizio_validita', '<=', now())
                ->orderBy('id_veicolo', 'ASC')
                ->get();

            Cache::put($cacheKey, $results, 60 * 60);
        } else {
            $results = Cache::get($cacheKey);
        }

        return $results;
    }

    public static function getCurrentExpiredList ($calculateNewCachedData = true) : mixed
    {
        $table=(new static)->getTable();
        $cacheKey = 'expired_' . $table . '_list';

        if ($calculateNewCachedData || !Cache::has($cacheKey)) {
            $results = DB::table($table)
                ->select([
                    'id as valid_id',
                    'id_veicolo',
                    'inizio_validita as valid_inizio_validita',
                    'fine_validita as valid_fine_validita'
                ])
                ->where('fine_validita', '<=', now())
                ->orderBy('id_veicolo', 'ASC')
                ->get();


            Cache::put($cacheKey, $results, 60 * 60);
        } else {
            $results = Cache::get($cacheKey);
        }

        return $results;
    }

    public static function getStartingNextList ($calculateNewCachedData = true) : mixed
    {
        $table=(new static)->getTable();
        $cacheKey = 'valid_' . $table . '_list';

        if ($calculateNewCachedData || !Cache::has($cacheKey)) {
            $results = DB::table($table)
                ->select(
                    'id as next_id',
                    'id_veicolo',
                    'inizio_validita as next_inizio_validita',
                    'fine_validita as next_fine_validita')
                    ->where('inizio_validita', '<=', now())
                    ->orderBy('id_veicolo', 'ASC')
                    ->get();


            foreach ($results as $item) {
                if (!isset($Dictionary[$item->id_veicolo])) {
                    $Dictionary[$item->id_veicolo] = [];
                }

                $Dictionary[$item->id_veicolo][] = $item;
            }

            Cache::put($cacheKey, $Dictionary, 60 * 60);
        } else {
            $Dictionary = Cache::get($cacheKey);
        }

        return $Dictionary;
    }

    public static function getExpiredList() {
        $records = DB::table(static::$tableName)
            ->select([
                'id AS expired_id',
                'id_veicolo',
                'inizio_validita AS expired_inizio_validita',
                'fine_validita AS expired_fine_validita'
            ])
            ->where('fine_validita', '<=', now())
            ->orderBy('id_veicolo', 'ASC')
            ->orderByDesc('fine_validita')
            ->get();

        return $records->groupBy('id_veicolo')->map(function ($group) {
            return $group->first();
        });
    }

    public static function getAlerts($search=null): \Illuminate\Support\Collection
    {
        $total_Start_time = microtime(true);

        $valid=static::getCurrentValidList();

        $expired=static::getExpiredList();

        $startingNext=static::getStartingNextList();
        dd($startingNext[1]);
        die();

        $query = DB::table(Veicolo::$tableName)
            ->leftJoin(Marca::$tableName, Veicolo::$tableName.'.id_marca', '=', Marca::$tableName.'.id')
            ->leftJoin(Modello::$tableName, function ($join) {
                $join->on(Veicolo::$tableName.'.id_modello', '=', Modello::$tableName.'.id')
                    ->on(Modello::$tableName.'.id_marca', '=', Marca::$tableName.'.id');
            })
            ->leftJoin(Targa::$tableName, Targa::$tableName.'.id_veicolo', '=', Veicolo::$tableName.'.id')
            ->select([
                Marca::$tableName.'.id as id_marca',
                Marca::$tableName.'.nome as marca',
                Modello::$tableName.'.id as id_modello',
                Modello::$tableName.'.nome as modello',
                Veicolo::$tableName.'.id as id_veicolo'
            ]);
        if ($search !== null) {
            $query->where(Targa::$tableName.'.targa', 'LIKE', '%' . $search . '%');
        }

        $result = $query->orderBy(Veicolo::$tableName.'.id', 'ASC')->get();

        echo "veicolo query took ".(microtime(true)-$start_time)." seconds.\n";



        /*
        // Iterate over the result and append properties if they exist in other dictionaries
        foreach ($result as $resItem) {
            $id = $resItem->id_veicolo;

            if (isset($validDict[$id])) {
                foreach ($validDict[$id] as $key => $value) {
                    $resItem->$key = $value;
                }
            }

            if (isset($expiredDict[$id])) {
                foreach ($expiredDict[$id] as $key => $value) {
                    $resItem->$key = $value;
                }
            }

            if (isset($startingNextDict[$id])) {
                foreach ($startingNextDict[$id] as $key => $value) {
                    $resItem->$key = $value;
                }
            }
        }
        */
        // At this point, $result will have the merged properties

        echo "total_Start_time took ".(microtime(true)-$total_Start_time)." seconds.\n";

        dd($result);

        die();

        return $result;
    }
}
