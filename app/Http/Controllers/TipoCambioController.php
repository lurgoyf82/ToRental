<?php

	namespace App\Http\Controllers;

	use App\Models\TipoCambio;
	use Illuminate\Http\Request;

	class TipoCambioController extends Controller
	{
		public function __construct()
		{
			$this->model = TipoCambio::class;
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
		public function show(TipoCambio $tipoCambio)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(TipoCambio $tipoCambio)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, TipoCambio $tipoCambio)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(TipoCambio $tipoCambio)
		{
			//
		}
//		public function search($search, $searchField = false, $exactIdVeicolo = false) {
//			$result = TipoCambio::search($search, $exactId, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
