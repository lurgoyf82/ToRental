<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Models\Targa;
	use App\Models\Tachigrafo;

	class TachigrafoController extends Controller
	{
		public function listExpiringRevisioniTachigrafi(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$search = $request->input('search',null);
			$order  = $request->input('order','livello');
			$page   = $request->input('page', 1);  // default to 1 if not provided

			$expiringRevisioniTachigrafi = Tachigrafo::getAggregatedAlerts($search, $order, $page);

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringRevisioniTachigrafi as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringRevisioniTachigrafi[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}
			return view('alert_revisione_tachigrafo', ['expiringRevisioniTachigrafi' => $expiringRevisioniTachigrafi]);
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
	}
