<?php

	namespace App\Http\Controllers;

	use App\Models\AlertBase;
	use App\Models\DestinazioneUso;
	use App\Models\DettaglioVeicolo;
	use App\Models\Marca;
	use App\Models\Modello;
	use App\Models\Societa;
	use App\Models\TipoAlimentazione;
	use App\Models\TipoAllestimento;
	use App\Models\TipoAsse;
	use App\Models\TipoCambio;
	use App\Models\TipoVeicolo;
	use Illuminate\Http\Request;
	use App\Models\Targa;
	use App\Models\Bollo;

	class BolloController extends Controller
	{
		public function __construct()
		{
			$this->model = Bollo::class;
		}
		public function listExpiringScadenzeBolli(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search',null);
			$order  = $request->input('order','livello');
			$page   = $request->input('page', 1);  // default to 1 if not provided

			$expiringScadenzeBolli = Bollo::getAggregatedAlerts($search, $order, $page);

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringScadenzeBolli as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringScadenzeBolli[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}
			return view('alert_scadenza_bollo', ['expiringScadenzeBolli' => $expiringScadenzeBolli]);
		}
//
//		/**
//		 * Display a listing of the resource.
//		 */
//		public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
//		{
//			$search = $request->input('search',null);
//			$order  = $request->input('order','id');
//			$page   = $request->input('page', 1);  // default to 1 if not provided
//
//
//			$result = Bollo::index($search, $order, $page);
//
//			$targaList= Targa::getTargaListByIdVeicolo();
//			foreach ($result as $key=>$alert) {
//				if(isset($targaList[$alert->id_veicolo])) {
//					$result[$key]->targa = $targaList[$alert->id_veicolo]->targa;
//				}
//			}
//			return view('list_bollo', ['list' => $result]);
//		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create($id_veicolo = null)
		{
			return view('create_bollo', ['id_veicolo' => $id_veicolo]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$validatedData = Bollo::validatePartial($request->all());

			// Remove 'targa' from $validatedData and create Veicolo
			$veicolo = Bollo::create($validatedData);

			AlertBase::clearCache(true);

			return redirect()->route('create_bollo')->with('success', 'Bollo created successfully.');
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
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Bollo::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}

		public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			// 'list_bollo' is the view name specific to this controller
			return $this->indexView($request, Bollo::class, 'list_bollo');
		}

	}
