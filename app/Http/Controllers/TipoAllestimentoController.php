<?php

	namespace App\Http\Controllers;

	use App\Models\TipoAllestimento;
	use Illuminate\Http\Request;

	class TipoAllestimentoController extends Controller
	{
		public function __construct()
		{
			$this->model = TipoAllestimento::class;
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
		public function show(TipoAllestimento $tipoAllestimento)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(TipoAllestimento $tipoAllestimento)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, TipoAllestimento $tipoAllestimento)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(TipoAllestimento $tipoAllestimento)
		{
			//
		}
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = TipoAllestimento::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
