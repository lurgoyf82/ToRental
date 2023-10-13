<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Support\Carbon;
	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\Facades\DB;

	class AlertBase extends BaseModel
	{
		use HasFactory;

		protected $table = 'assicurazione'; // Default table
		public static function getCurrentValidList ($calculateNewCachedData = false) : mixed
		{
			$table=(new static)->getTable();
			$cacheKey = 'valid_' . $table . '_list';

			if ($calculateNewCachedData || !Cache::has($cacheKey)) {
				$results = DB::table($table)
					->select(['id AS current_valid_id','id_veicolo','inizio_validita AS current_valid_inizio_validita','fine_validita AS current_valid_fine_validita'])
					->where('fine_validita', '>', now())
					->where('inizio_validita', '<=', now())
					->orderBy('id_veicolo', 'ASC')
					->get();

				$Dictionary=[];

				foreach ($results as $item) {
					if (!isset($Dictionary[$item->id_veicolo])) {
						$Dictionary[$item->id_veicolo] = [];
					}

					$Dictionary[$item->id_veicolo][] = $item;
				}

				Cache::tags($table)->put($cacheKey, $Dictionary, 60 * 60);
			} else {
				$Dictionary = Cache::tags($table)->get($cacheKey);
			}

			return $Dictionary;
		}
		public static function getStartingNextList ($calculateNewCachedData = false) : mixed
		{
			$table=(new static)->getTable();
			$cacheKey = 'valid_' . $table . '_list';

			if ($calculateNewCachedData || !Cache::has($cacheKey)) {
				echo "ricalcolo cache getStartingNextList \n";
				$results = DB::table($table)
					->select(['id AS next_id','id_veicolo','inizio_validita AS next_inizio_validita','fine_validita AS next_fine_validita'])
					->where('inizio_validita', '>=', now())
					->orderBy('id_veicolo', 'ASC')
					->get();

				$Dictionary=[];

				foreach ($results as $item) {
					if (!isset($Dictionary[$item->id_veicolo])) {
						$Dictionary[$item->id_veicolo] = [];
					}

					$Dictionary[$item->id_veicolo][] = $item;
				}

				Cache::tags($table)->put($cacheKey, $Dictionary, 60 * 60);
			} else {

				echo "GET cache getStartingNextList \n";
				$Dictionary = Cache::tags($table)->get($cacheKey);
			}

			return $Dictionary;
		}
		public static function getExpiredList($calculateNewCachedData = false) : mixed {
			$table=(new static)->getTable();
			$cacheKey = 'valid_' . $table . '_list';
			echo '$calculateNewCachedData = '.($calculateNewCachedData ?'true':'false')." ".'Cache::has('."$cacheKey".') = '. (Cache::has($cacheKey) ?'true':'false')."\n";
			if ($calculateNewCachedData || !Cache::has($cacheKey)) {
				$results = DB::table(static::$tableName)
					->select(['id AS expired_id',
										'id_veicolo',
										'inizio_validita AS expired_inizio_validita',
										'fine_validita AS expired_fine_validita'])
					->where('fine_validita', '<=', now())
					->orderBy('id_veicolo', 'ASC')
					->orderBy('fine_validita', 'DESC')
					->get();

				$Dictionary=[];

				foreach ($results as $item) {
					if (!isset($Dictionary[$item->id_veicolo])) {
						$Dictionary[$item->id_veicolo] = [];
					}

					$Dictionary[$item->id_veicolo][] = $item;
				}

				Cache::tags($table)->put($cacheKey, $Dictionary, 60 * 60);
			} else {
				$Dictionary = Cache::tags($table)->get($cacheKey);
			}

			return $Dictionary;
		}

		public static function getAlertsTwo($search=null): \Illuminate\Support\Collection
		{
			$start_time=microtime(true);
			for($i=0;$i<100;$i++) {
				$valid = static::getCurrentValidList();
			}
			$end_valid_time=microtime(true);
			for($i=0;$i<100;$i++) {
				$expired = static::getExpiredList();
			}
			$end_expired_time=microtime(true);
			for($i=0;$i<100;$i++) {
				$startingNext = static::getStartingNextList();
			}
			$end_startingNext_time=microtime(true);

			echo "valid execution time :".($end_valid_time-$start_time)."\n";
			echo "expired execution time :".($end_expired_time-$end_valid_time)."\n";
			echo "starting next execution time :".($end_startingNext_time-$end_expired_time)."\n";
			echo "total execution time :".($end_startingNext_time-$start_time)."\n";
			die() ;

			$query = DB::table(Veicolo::getTableName())
				->leftJoin(Marca::getTableName(), Veicolo::getTableName() . '.id_marca', '=', Marca::getTableName() . '.id')
				->leftJoin(Modello::getTableName(), function ($join) {
					$join->on(Veicolo::getTableName() . '.id_modello', '=', Modello::getTableName() . '.id')
						->on(Modello::getTableName() . '.id_marca', '=', Marca::getTableName() . '.id');
				})
				->leftJoin(Targa::getTableName(), Targa::getTableName() . '.id_veicolo', '=', Veicolo::getTableName() . '.id')
				->select([
					Marca::getTableName() . '.id as id_marca',
					Marca::getTableName() . '.nome as marca',
					Modello::getTableName() . '.id as id_modello',
					Modello::getTableName() . '.nome as modello',
					Veicolo::getTableName() . '.id as id_veicolo'
				]);
			if ($search !== null) {
				$query->where(Targa::getTableName() . '.targa', 'LIKE', '%' . $search . '%');
			}

			$result = $query->orderBy(Veicolo::getTableName() . '.id', 'ASC')->get();

			foreach($result as $key => $vehicle) {
				$vehicle->livello = null;
				$vehicle->next = null;
				$vehicle->id = null;

				if(@isset($valid[$vehicle->id_veicolo])) {
					$vehicle->valid = $valid[$vehicle->id_veicolo];
					foreach ($vehicle->valid as $contract) {
						$livello = Carbon::parse($contract->current_valid_inizio_validita)->diffInDays(Carbon::now());
						if($livello>$vehicle->livello) {
							$vehicle->livello = $livello;
							$vehicle->id = $contract->current_valid_id;
						}
					}
				} else {
					$vehicle->valid = false;
				}

				if(@isset($startingNext[$vehicle->id_veicolo])) {
					$vehicle->startingNext = $startingNext[$vehicle->id_veicolo];
					foreach ($vehicle->startingNext as $contract) {
						$next = Carbon::parse($contract->next_inizio_validita)->diffInDays(Carbon::now());
						if($next<$vehicle->next) {
							$vehicle->next = $next;
							$vehicle->id = $contract->next_id;
						}
					}
				} else {
					$vehicle->startingNext = false;
				}

				if(@isset($expired[$vehicle->id_veicolo])) {
					$vehicle->expired = $expired[$vehicle->id_veicolo];
					if($vehicle->livello===null) {
						foreach ($vehicle->expired as $contract) {
							$livello = -(Carbon::now()->diffInDays($contract->expired_fine_validita));
							if($livello>$vehicle->livello) {
								$vehicle->livello = $livello;
								$vehicle->id = $contract->expired_id;
							}
						}
					}
				} else {
					$vehicle->expired = false;
				}
			}

			return ($result->sortBy('livello'));
		}
		public static function clearCache($all=false) {
			if($all==true) {
				Cache::flush();
			} else {
				Cache::tags((new static)->getTable())->flush();
			}
		}


		public static function getAllCacheTags()
		{
			$redis = Cache::connection();

			// Get all keys matching the tag pattern
			$keys = $redis->keys('cache:tag:*');

			// Extract the tags from the keys
			$tags = array_map(function ($key) {
				return str_replace('cache:tag:', '', $key);
			}, $keys);

			return $tags;
		}

	}
