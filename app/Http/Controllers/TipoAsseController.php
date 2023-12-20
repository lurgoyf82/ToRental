<?php

	namespace App\Http\Controllers;

	use App\Models\TipoAsse;
	use Illuminate\Http\Request;

	class TipoAsseController extends Controller
	{
		public function __construct()
		{
			$this->model = TipoAsse::class;
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
		public function show(TipoAsse $tipoAsse)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(TipoAsse $tipoAsse)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, TipoAsse $tipoAsse)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(TipoAsse $tipoAsse)
		{
			//
		}
//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = TipoAsse::search($search, $searchField, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
