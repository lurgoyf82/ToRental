<?php

	namespace App\Http\Controllers;

	use App\Models\Societa;
	use Illuminate\Http\Request;

	class SocietaController extends Controller
	{
		public function __construct()
		{
			$this->model = Societa::class;
		}
		/**
		 * Display a listing of the resource.
		 */
		public function index()
		{
			$lista_societa = Societa::getAggregatedAlertsList();
			return view('societa.index', ['lista_societa' => $lista_societa]);
		}

		/**
		 * Show the form for creating a new resource.
		 */
		public function create()
		{
			return view('societa.create');
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$data = $request->validate([
				'nome' => 'required|string|max:256',
			]);

			Societa::create($data);

			return redirect()->route('societa.index')->with('success', 'Societa created successfully!');
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
		public function edit($id)
		{
			$societa = Societa::findOrFail($id);
			return view('societa.edit', ['societa' => $societa]);
		}




		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, $id)
		{
			$data = $request->validate([
				'nome' => 'required|string|max:256',
			]);

			$societa = Societa::findOrFail($id);
			$societa->update($data);

			return redirect()->route('societa.index')->with('success', 'Societa updated successfully!');
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy($id)
		{
			$societa = Societa::findOrFail($id);
			$societa->delete();

			return redirect()->route('societa.index')->with('success', 'Societa deleted successfully!');
		}

//		public function search($search, $exactId = false, $exactIdVeicolo = false) {
//			$result = Societa::search($search, $exactId, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
