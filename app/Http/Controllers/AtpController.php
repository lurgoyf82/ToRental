<?php

	namespace App\Http\Controllers;

	use App\Models\AlertBase;
	use Illuminate\Http\Request;
	use App\Models\Targa;
	use App\Models\Atp;

	class AtpController extends Controller
	{
		public function __construct()
		{
			$this->model = Atp::class;
		}
		public function listExpiringRevisioniAtp(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search',null);
			$order  = $request->input('order','livello');
			$page   = $request->input('page', 1);  // default to 1 if not provided

			$expiringRevisioniAtp = Atp::getAggregatedAlertsList($search, $order, $page);

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringRevisioniAtp as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringRevisioniAtp[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}
			return view('alert_revisione_atp', ['expiringRevisioniAtp' => $expiringRevisioniAtp]);
		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create($id_veicolo = null)
		{
			return view('create_atp', ['id_veicolo' => $id_veicolo]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$validatedData = Atp::validatePartial($request->all());

			// Remove 'targa' from $validatedData and create Veicolo
			$veicolo = Atp::create($validatedData);

			AlertBase::clearCache(true);

			return redirect()->route('create_atp')->with('success', 'Atp created successfully.');
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
			return $this->indexView($request, Atp::class, 'list_atp');
		}
	}
