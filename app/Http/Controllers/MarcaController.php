<?php

	namespace App\Http\Controllers;

	use App\Models\Marca;
	use Illuminate\Http\Request;

	class MarcaController extends Controller
	{
		public function __construct()
		{
			$this->model = Marca::class;
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
		public function show(Marca $marca)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(Marca $marca)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, Marca $marca)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(Marca $marca)
		{
			//
		}

//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Marca::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
