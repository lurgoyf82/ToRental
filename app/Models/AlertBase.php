<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Carbon;
	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Redis;

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

				Cache::put($cacheKey, $Dictionary, 60 * 60);
			} else {
				$Dictionary = Cache::get($cacheKey);
			}

			return $Dictionary;
		}
		public static function getStartingNextList ($calculateNewCachedData = false) : mixed
		{
			$table=(new static)->getTable();
			$cacheKey = 'startingNext_' . $table . '_list';

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

				Cache::put($cacheKey, $Dictionary, 60 * 60);
			} else {

				$Dictionary = Cache::get($cacheKey);
			}

			return $Dictionary;
		}
		public static function getExpiredList($calculateNewCachedData = false) : mixed {
			$table=(new static)->getTable();
			$cacheKey = 'expired_' . $table . '_list';

			if ($calculateNewCachedData || !Cache::has($cacheKey)) {
				$results = DB::table($table)
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

				Cache::put($cacheKey, $Dictionary, 60 * 60);
			} else {
				$Dictionary = Cache::get($cacheKey);
			}

			return $Dictionary;
		}

		public static function getAggregatedAlerts($search=null,$order='livello',$page=1,$slice=true): LengthAwarePaginator
		{
			$valid = static::getCurrentValidList();
			$expired = static::getExpiredList();
			$startingNext = static::getStartingNextList();

			$page = intval($page);
			if ($page <= 0 || !is_int($page)) {
				$page = 1;
			}

			$order=explode('_',$order);

			switch (strtolower($order[0])) {
				case 'marca':
					$orderBy = Marca::getTableName() . '.nome';
					break;
				case 'modello':
					$orderBy = Modello::getTableName() . '.nome';
					break;
				case 'targa':
					$orderBy = Targa::getTableName() . '.targa';
					break;
				default:
					$orderBy = 'livello';
					break;
			}

			if(array_key_exists(1,$order,)&&strtolower($order[1])=='desc') {
				$orderDirection='DESC';
			} else {
				$orderDirection='ASC';
			}

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
				$query->where(function ($query) use ($search) {
					$query->where(Targa::getTableName() . '.targa', 'LIKE', '%' . $search . '%')
						->orWhere(Marca::getTableName() . '.nome', 'LIKE', '%' . $search . '%')
						->orWhere(Modello::getTableName() . '.nome', 'LIKE', '%' . $search . '%');
				});
			}

			//$query->offset(($page - 1) * AlertBase::$itemsPerPage)->limit(AlertBase::$itemsPerPage);

			if ($orderBy!=='livello') {
				$result = $query->orderBy($orderBy, $orderDirection)->get();
			} else {
				$result = $query->orderBy(Veicolo::getTableName() . '.id', 'ASC')->get();
			}

			foreach($result as $key => $vehicle) {
				$vehicle->livello = null;
				$vehicle->next = null;
				$vehicle->id = null;

				if(@isset($valid[$vehicle->id_veicolo])) {
					$vehicle->valid = $valid[$vehicle->id_veicolo];
					foreach ($vehicle->valid as $contract) {
						$livello = Carbon::parse($contract->current_valid_fine_validita)->diffInDays(Carbon::now());
						if($livello>$vehicle->livello) {
							$vehicle->livello = $livello;
							$vehicle->inizio_validita = $contract->current_valid_inizio_validita;
							$vehicle->fine_validita = $contract->current_valid_fine_validita;
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
							$vehicle->inizio_validita = $contract->next_inizio_validita;
							$vehicle->fine_validita = $contract->next_fine_validita;
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
								$vehicle->inizio_validita = $contract->expired_inizio_validita;
								$vehicle->fine_validita = $contract->expired_fine_validita;
								$vehicle->id = $contract->expired_id;
							}
						}
					}
				} else {
					$vehicle->expired = false;
				}

				if($vehicle->livello > Alert::$thirdThreshold) {
					unset($result[$key]);
				}
			}

			if ($orderBy=='livello') {
				if($orderDirection=='DESC') {
					$result=($result->sortByDesc('livello'));
				} else {
					$result=($result->sortBy('livello'));
				}
			}

			// Manually slice the results for pagination
			$offset = ($page - 1) * AlertBase::$itemsPerPage;
			if($slice) {
				$itemsForCurrentPage = $result->slice($offset, AlertBase::$itemsPerPage);
			} else {
				$itemsForCurrentPage = $result;
			}

			return new LengthAwarePaginator(
				$itemsForCurrentPage,
				$result->count(),
				AlertBase::$itemsPerPage,
				$page,
				['path' => LengthAwarePaginator::resolveCurrentPath()]
			);
		}

		/*
		public static function getAggregatedAlerts($search=null): \Illuminate\Support\Collection
		{
			$valid = static::getCurrentValidList();
			$expired = static::getExpiredList();
			$startingNext = static::getStartingNextList();

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

			//$listaTarghe=Targa::getTargaListByIdVeicolo();

			//foreach($result as $key=>$value) {
			//	if(isset($listaTarghe[$value->id_veicolo])) {
			//		$result[$key]->targa=$listaTarghe[$value->id_veicolo]->targa;
			//	} else {
			//		$result[$key]->targa=null;
			//	}
			//}

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
			// Filter the results in Laravel based on the $search parameter
			//if ($search !== null) {
			//	$result = $result->filter(function ($vehicle) use ($search) {
			//		return str_contains($vehicle->targa, $search);
			//	});
			//}
			return ($result->sortBy('livello'));
		}
		*/
		public static function clearCache($all=false) {

			if($all) {
				Cache::flush();
			} else {
				$table=(new static)->getTable();

				$valid = 'valid_' . $table . '_list';
				$next = 'startingNext_' . $table . '_list';
				$expired = 'expired_' . $table . '_list';

				Cache::forget($valid);
				Cache::forget($next);
				Cache::forget($expired);
				Cache::forget('alerts');
			}
		}
	}
