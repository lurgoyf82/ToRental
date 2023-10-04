<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
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
				$Dictionary = Cache::tags($table)->get($cacheKey);
			}

			return $Dictionary;
		}
		public static function getExpiredList($calculateNewCachedData = false) : mixed {
			$table=(new static)->getTable();
			$cacheKey = 'valid_' . $table . '_list';

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

		public static function getAlerts($search=null): \Illuminate\Support\Collection
		{
			$valid = static::getCurrentValidList(true);
			$expired = static::getExpiredList(true);
			$startingNext = static::getStartingNextList(true);


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

			foreach($result as $key => $row) {
				if(@isset($valid[$row->id_veicolo])) {
					$row->valid = $valid;
				} else {
					$row->valid = false;
				}

				if(@isset($expired[$row->id_veicolo])) {
					$row->expired = $expired;
				} else {
					$row->expired = false;
				}

				if(@isset($startingNext[$row->id_veicolo])) {
					$row->startingNext = $startingNext;
				} else {
					$row->startingNext = false;
				}
			}

			return ($result);
		}
		public static function getAlertsTwo($search=null): \Illuminate\Support\Collection
		{
			$valid = static::getCurrentValidList(true);
			$expired = static::getExpiredList(true);
			$startingNext = static::getStartingNextList(true);

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
		public static function flushCache() {
			Cache::tags((new static)->getTable())->flush();
		}

	}
