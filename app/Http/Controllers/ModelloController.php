<?php

	namespace App\Http\Controllers;

	use App\Models\Modello;
	use Illuminate\Http\Request;

	class ModelloController extends Controller
	{
		public function __construct()
		{
			$this->model = Modello::class;
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
		public function show(Modello $modello)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(Modello $modello)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, Modello $modello)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(Modello $modello)
		{
			//
		}
		/*
		public function getModelloByMarca($id)
		{
			$modello = Modello::where('marca_id', $id)->get();
			return response()->json($modello);
		}
		*/
		public function getModelloByMarca($id) {
			$modelli = Modello::where('id_marca', $id)->pluck('nome', 'id');
			return response()->json($modelli);
		}
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Modello::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}

	}
