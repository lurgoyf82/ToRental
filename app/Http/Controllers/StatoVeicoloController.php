<?php

	namespace App\Http\Controllers;

	use App\Models\StatoVeicolo;
	use Illuminate\Http\Request;

	class StatoVeicoloController extends Controller
	{
		public function __construct()
		{
			$this->model = StatoVeicolo::class;
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
		public function show(StatoVeicolo $statoVeicolo)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(StatoVeicolo $statoVeicolo)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, StatoVeicolo $statoVeicolo)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(StatoVeicolo $statoVeicolo)
		{
			//
		}

//		public function search($search, $searchField = false, $searchFieldVeicolo = false) {
//			$result = StatoVeicolo::search($search, $searchField, $searchFieldVeicolo);
//			return response()->json($result);
//		}
	}
