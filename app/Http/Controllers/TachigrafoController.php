<?php

	namespace App\Http\Controllers;

	use App\Models\AlertBase;
	use Illuminate\Http\Request;
	use App\Models\Targa;
	use App\Models\Tachigrafo;

	class TachigrafoController extends Controller
	{
		public function __construct()
		{
			$this->model = Tachigrafo::class;
		}
		public function listExpiringRevisioniTachigrafi(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search',null);
			$order  = $request->input('order','livello');
			$page   = $request->input('page', 1);  // default to 1 if not provided

			$expiringRevisioniTachigrafi = Tachigrafo::getAggregatedAlertsList($search, $order, $page);

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringRevisioniTachigrafi as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringRevisioniTachigrafi[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}
			return view('alert_revisione_tachigrafo', ['expiringRevisioniTachigrafi' => $expiringRevisioniTachigrafi]);
		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create($id_veicolo = null)
		{
			return view('create_tachigrafo', ['id_veicolo' => $id_veicolo]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$validatedData = Tachigrafo::validatePartial($request->all());

			// Remove 'targa' from $validatedData and create Veicolo
			$veicolo = Tachigrafo::create($validatedData);

			AlertBase::clearCache(true);

			return redirect()->route('create_tachigrafo')->with('success', 'Tachigrafo created successfully.');
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
			return $this->indexView($request, Tachigrafo::class, 'list_tachigrafo');
		}

//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Tachigrafo::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
