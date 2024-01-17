<?php

	namespace App\Http\Controllers;

	use App\Models\DestinazioneUso;
	use App\Models\DettaglioVeicolo;
	use App\Models\Marca;
	use App\Models\Modello;
	use App\Models\Multa;
	use App\Models\Societa;
	use App\Models\TipoAlimentazione;
	use App\Models\TipoAllestimento;
	use App\Models\TipoAsse;
	use App\Models\TipoCambio;
	use App\Models\TipoVeicolo;
	use Illuminate\Http\Request;

	class MultaController extends Controller
	{
		public function __construct()
		{
			$this->model = Multa::class;
		}
		//controller for alert leasing
		public function alert() {
			return view('multa_alert');
		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create()
		{

			//list to handle id_veicolo
			$lista_veicolo = DettaglioVeicolo::orderBy('id')->get();
			//list to handle id_propietario
			$lista_societa = Societa::orderBy('nome')->get();
			//list to handle id_tipo_veicolo
			$lista_tipo_veicolo = TipoVeicolo::orderBy('nome')->get();
			//list to handle id_tipo_allestimento
			$lista_tipo_allestimento = TipoAllestimento::orderBy('nome')->get();
			//list to handle id_marca
			$lista_marca = Marca::orderBy('nome')->get();
			//list to handle id_modello
			$lista_modello = Modello::orderBy('nome')->get();
			//list to handle tipo_asse
			$lista_tipo_asse = TipoAsse::orderBy('nome')->get();
			//list to handle tipo_cambio
			$lista_tipo_cambio = TipoCambio::orderBy('nome')->get();
			//list to handle alimentazione
			$lista_alimentazione = TipoAlimentazione::orderBy('nome')->get();
			//list to handle destinazione_uso
			$lista_destinazione_uso = DestinazioneUso::orderBy('nome')->get();

			return view('create_decorazione', ['lista_veicolo' => $lista_veicolo,
				'lista_societa' => $lista_societa,
				'lista_tipo_veicolo' => $lista_tipo_veicolo,
				'lista_tipo_allestimento' => $lista_tipo_allestimento,
				'lista_marca' => $lista_marca,
				'lista_modello' => $lista_modello,
				'lista_tipo_asse' => $lista_tipo_asse,
				'lista_tipo_cambio' => $lista_tipo_cambio,
				'lista_alimentazione' => $lista_alimentazione,
				'lista_destinazione_uso' => $lista_destinazione_uso]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			//
		}

		/**
		 * Display the specified resource.
		 */
		public function show(string $id)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(string $id)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, string $id)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(string $id)
		{
			//
		}
		/**
		 * Search the specified resource from storage.
		 */

		/**
		 * Display a listing of the resource.
		 */
		public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			return $this->indexView($request, Multa::class, 'list_multa');
		}
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Multa::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
