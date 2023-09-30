<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Models\Targa;
	use App\Models\Assicurazione;

	class AssicurazioneController extends Controller
	{
		public function listExpiringPolizzeAssicurative(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			$expiringPolizzeAssicurative = Assicurazione::getExpiringPolizzeAssicurative($request->input('search'));

			$targaList= Targa::getTargaListByIdVeicolo();
			foreach ($expiringPolizzeAssicurative as $key=>$alert) {
				if(isset($targaList[$alert->id_veicolo])) {
					$expiringPolizzeAssicurative[$key]->targa = $targaList[$alert->id_veicolo]->targa;
				}
			}
			return view('alert_polizza_assicurativa', ['expiringPolizzeAssicurative' => $expiringPolizzeAssicurative]);
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
