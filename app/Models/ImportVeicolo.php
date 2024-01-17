<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportVeicolo extends BaseModel
{
	use HasFactory;

	protected $table = 'import_veicolo';
	protected $fillable = ['targa','telaio','societa','tipologia_veicolo','allestimento','marca','modello',
		'colore','lunghezza','larghezza','massa','portata','cilindrata','potenza','numero_assi','tipo_asse',
		'tipo_cambio','alimentazione','data_immatricolazione','destinazione_uso'];
	public $timestamps = true;


	public static function validate(Request $request): array
	{
		$validatedData = $request->validate([
			'targa' => 'required|string|max:10',
			'telaio' => 'required|string|max:17',
			'societa' => 'required|string|max:255',
			'tipologia_veicolo' => 'required|string|max:255',
			'allestimento' => 'required|string|max:255',
			'marca' => 'required|string|max:255',
			'modello' => 'required|string|max:255',
			'colore' => 'required|string|max:255',
			'lunghezza' => 'required|numeric',
			'larghezza' => 'required|numeric',
			'massa' => 'required|numeric',
			'portata' => 'required|numeric',
			'cilindrata' => 'required|numeric',
			'potenza' => 'required|numeric',
			'numero_assi' => 'required|numeric',
			'tipo_asse' => 'required|string|max:255',
			'tipo_cambio' => 'required|string|max:255',
			'alimentazione' => 'required|string|max:255',
			'data_immatricolazione' => 'required|date',
			'destinazione_uso' => 'required|string|max:255',
		]);

		return $validatedData;
	}

	public static function create($validatedData): void
	{
		$veicoloData = Arr::except($validatedData, ['targa', 'data_immatricolazione']);
		$veicolo = DettaglioVeicolo::create($veicoloData);

		$targaData = [
			'id_veicolo' => $veicolo->id,
			'targa' => $validatedData['targa'],
			'data_immatricolazione' => $validatedData['data_immatricolazione']
		];

		Targa::create($targaData);
	}
}
