<?php

	namespace App\Http\Controllers;

	use App\Models\Leasing;
	use Illuminate\Http\Request;

	class LeasingController extends Controller
	{
		public function __construct()
		{
			$this->model = Leasing::class;
		}
		//controller for alert leasing
		public function alert() {
			return view('leasing_alert');
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
		public function show(string $id)
		{
			//
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(string $id)
		{
			//
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, string $id)
		{
			//
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(string $id)
		{
			//
		}
//		public function search($search, $exactId = false, $exactIdVeicolo = false) {
//			$result = Leasing::search($search, $exactId, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
