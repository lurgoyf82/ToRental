<?php

	namespace App\Http\Controllers;

	use App\Models\Targa;
	use Illuminate\Http\Request;

	class TargaController extends Controller
	{
		public function __construct()
		{
			$this->model = Targa::class;
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
		public function show(Targa $targa)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(Targa $targa)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, Targa $targa)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(Targa $targa)
		{
			//
		}

//		public function search($search, $exactId = false, $exactIdVeicolo = false) {
//			$result = Targa::search($search, $exactId, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
