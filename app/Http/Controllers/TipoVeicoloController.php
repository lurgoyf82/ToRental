<?php

	namespace App\Http\Controllers;

	use App\Models\TipoVeicolo;
	use Illuminate\Http\Request;

	class TipoVeicoloController extends Controller
	{
		public function __construct()
		{
			$this->model = TipoVeicolo::class;
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
		public function show(TipoVeicolo $tipoVeicolo)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(TipoVeicolo $tipoVeicolo)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, TipoVeicolo $tipoVeicolo)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(TipoVeicolo $tipoVeicolo)
		{
			//
		}
//		public function search($search, $exactId = false, $exactIdVeicolo = false) {
//			$result = TipoVeicolo::search($search, $exactId, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
