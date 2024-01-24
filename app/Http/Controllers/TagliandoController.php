<?php

	namespace App\Http\Controllers;

	use App\Models\AlertBase;
	use App\Models\Assicurazione;
	use App\Models\DestinazioneUso;
	use App\Models\DettaglioVeicolo;
	use App\Models\Marca;
	use App\Models\Modello;
	use App\Models\Societa;
	use App\Models\Tagliando;
	use App\Models\Targa;
	use App\Models\TipoAlimentazione;
	use App\Models\TipoAllestimento;
	use App\Models\TipoAsse;
	use App\Models\TipoCambio;
	use App\Models\TipoVeicolo;
	use Illuminate\Http\Request;

	class TagliandoController extends Controller
	{
		public function __construct()
		{
			$this->model = Tagliando::class;
		}
		public function listExpiringTagliandi(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search',null);
			$order  = $request->input('order','livello');
			$page   = $request->input('page', 1);  // default to 1 if not provided

			$expiringPolizzeAssicurative = Assicurazione::getAggregatedAlertsList($search, $order, $page);

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringPolizzeAssicurative as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringPolizzeAssicurative[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}

			return view('alert_polizza_assicurativa', ['expiringPolizzeAssicurative' => $expiringPolizzeAssicurative]);
		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create($id_veicolo = null)
		{
			return view('create_tagliando', ['id_veicolo' => $id_veicolo]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$validatedData = Tagliando::validatePartial($request->all());
			try {
				Tagliando::create($validatedData);

				// Assuming AlertBase::clearCache(true) is safe to call even if create fails.
				AlertBase::clearCache(true);

				return redirect()->route('create_tagliando')->with('success', 'Tagliando created successfully.');
			} catch (\Exception $e) {
				// Log the error message for debugging (optional but recommended)
				\Log::error("Error creating Tagliando: " . $e->getMessage());

				// Redirect back with input data and error message
				return redirect()->back()->withInput()->with('error', 'Error occurred while creating Tagliando.');
			}


			$veicolo = Tagliando::create($validatedData);

			AlertBase::clearCache(true);

			return redirect()->route('create_tagliando')->with('success', 'Tagliando created successfully.');
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
			return $this->indexView($request, Tagliando::class, 'list_tagliando');
		}
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Tagliando::search($search, $searchField, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
