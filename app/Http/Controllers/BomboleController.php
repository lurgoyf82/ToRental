<?php

	namespace App\Http\Controllers;

	use App\Models\AlertBase;
	use Illuminate\Http\Request;
	use App\Models\Targa;
	use App\Models\Bombole;

	class BomboleController extends Controller
	{
		public function __construct()
		{
			$this->model = Bombole::class;
		}
		public function listExpiringRevisioniBombole(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search',null);
			$order  = $request->input('order','livello');
			$page   = $request->input('page', 1);  // default to 1 if not provided

			$expiringRevisioniBombole = Bombole::getAggregatedAlertsList($search, $order, $page);

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringRevisioniBombole as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringRevisioniBombole[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}
			return view('alert_revisione_bombole', ['expiringRevisioniBombole' => $expiringRevisioniBombole]);
		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create($id_veicolo = null)
		{
			return view('create_bombole', ['id_veicolo' => $id_veicolo]);

			////list to handle id_veicolo
			//$lista_veicolo = DettaglioVeicolo::orderBy('id')->get();
			////list to handle id_propietario
			//$lista_societa = Societa::orderBy('nome')->get();
			////list to handle id_tipo_veicolo
			//$lista_tipo_veicolo = TipoVeicolo::orderBy('nome')->get();
			////list to handle id_tipo_allestimento
			//$lista_tipo_allestimento = TipoAllestimento::orderBy('nome')->get();
			////list to handle id_marca
			//$lista_marca = Marca::orderBy('nome')->get();
			////list to handle id_modello
			//$lista_modello = Modello::orderBy('nome')->get();
			////list to handle tipo_asse
			//$lista_tipo_asse = TipoAsse::orderBy('nome')->get();
			////list to handle tipo_cambio
			//$lista_tipo_cambio = TipoCambio::orderBy('nome')->get();
			////list to handle alimentazione
			//$lista_alimentazione = TipoAlimentazione::orderBy('nome')->get();
			////list to handle destinazione_uso
			//$lista_destinazione_uso = DestinazioneUso::orderBy('nome')->get();
			//
			//return view('create_assicurazione', ['id_veicolo' => $id_veicolo,
			//	'lista_veicolo' => $lista_veicolo,
			//	'lista_societa' => $lista_societa,
			//	'lista_tipo_veicolo' => $lista_tipo_veicolo,
			//	'lista_tipo_allestimento' => $lista_tipo_allestimento,
			//	'lista_marca' => $lista_marca,
			//	'lista_modello' => $lista_modello,
			//	'lista_tipo_asse' => $lista_tipo_asse,
			//	'lista_tipo_cambio' => $lista_tipo_cambio,
			//	'lista_alimentazione' => $lista_alimentazione,
			//	'lista_destinazione_uso' => $lista_destinazione_uso]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$validatedData = Bombole::validatePartial($request->all());

			// Remove 'targa' from $validatedData and create Veicolo
			$veicolo = Bombole::create($validatedData);

			AlertBase::clearCache(true);

			return redirect()->route('create_bombole')->with('success', 'Revisione Bombole created successfully.');
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
		 * Display a listing of the resource.
		 */
		public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			return $this->indexView($request, Bombole::class, 'list_bombole');
		}
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Bollo::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
