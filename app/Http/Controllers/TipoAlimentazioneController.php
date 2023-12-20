<?php

	namespace App\Http\Controllers;

	use App\Models\TipoAlimentazione;
	use Illuminate\Http\Request;

	class TipoAlimentazioneController extends Controller
	{
		public function __construct()
		{
			$this->model = TipoAlimentazione::class;
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
		public function show(TipoAlimentazione $tipoAlimentazione)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(TipoAlimentazione $tipoAlimentazione)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, TipoAlimentazione $tipoAlimentazione)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(TipoAlimentazione $tipoAlimentazione)
		{
			//
		}

//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = TipoAlimentazione::search($search, $exactId, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
