<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Models\Targa;
	use App\Models\Noleggio;

	class NoleggioController extends Controller
	{
		public function __construct()
		{
			$this->model = Noleggio::class;
		}
		public function listExpiringContrattiNoleggio(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search',null);
			$order  = $request->input('order','livello');
			$page   = $request->input('page', 1);  // default to 1 if not provided

			$expiringContrattiNoleggio = Noleggio::getAggregatedAlertsList($search, $order, $page);

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringContrattiNoleggio as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringContrattiNoleggio[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}
			return view('alert_contratto_noleggio', ['expiringContrattiNoleggio' => $expiringContrattiNoleggio]);
		}

		/**
		 * Display a listing of the resource.
		 */
		public function index()
		{
			//
		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create()
		{
			//
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
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Noleggio::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
