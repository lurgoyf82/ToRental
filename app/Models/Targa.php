<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;

	class Targa extends BaseModel
	{
		use HasFactory;

		protected $table = 'targa';
		public static string $tableName = 'targa';
		protected $fillable = ['id_veicolo', 'targa', 'data_immatricolazione'];

		//lists all the targa grouped and concatenated by id_veicolo
		public static function getTargaListByIdVeicolo() {
			$query = DB::table('targa')
				->select([
					'targa.id_veicolo',
					DB::raw('GROUP_CONCAT(targa.targa SEPARATOR ", ") as targa')
				])
				->groupBy('targa.id_veicolo')
				->get();

			$return=array();

			foreach ($query as $key=>$value) {
				$return[$value->id_veicolo]=$value;
			}

			return $return;
		}

		public static function create($validatedData) {
			$targa = new Targa();
			$targa->id_veicolo = $validatedData['id_veicolo'];
			$targa->targa = $validatedData['targa'];
			$targa->data_immatricolazione = $validatedData['data_immatricolazione'];
			$targa->save();
			return $targa;
		}

	}
