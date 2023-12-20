<?php

	namespace App\Http\Controllers;

	use App\Models\Telaio;
	use Illuminate\Http\Request;

	class TelaioController extends Controller
	{
		public function __construct()
		{
			$this->model = Telaio::class;
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
		public function show(Telaio $telaio)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(Telaio $telaio)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, Telaio $telaio)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(Telaio $telaio)
		{
			//
		}

//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = Telaio::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
