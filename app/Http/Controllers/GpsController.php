<?php

	namespace App\Http\Controllers;

	use App\Models\AlertBase;
	use App\Models\DestinazioneUso;
	use App\Models\DettaglioVeicolo;
	use App\Models\Gps;
	use App\Models\Marca;
	use App\Models\Modello;
	use App\Models\Societa;
	use App\Models\TipoAlimentazione;
	use App\Models\TipoAllestimento;
	use App\Models\TipoAsse;
	use App\Models\TipoCambio;
	use App\Models\TipoVeicolo;
	use Illuminate\Http\Request;

	class GpsController extends Controller
	{
		public function __construct()
		{
			$this->model = Gps::class;
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
		public function create($id_veicolo = null)
		{
			return view('create_gps', ['id_veicolo' => $id_veicolo]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$validatedData = Gps::validatePartial($request->all());
			try {
				Gps::create($validatedData);

				// Assuming AlertBase::clearCache(true) is safe to call even if create fails.
				AlertBase::clearCache(true);

				return redirect()->route('create_gps')->with('success', 'GPS created successfully.');
			} catch (\Exception $e) {
				// Log the error message for debugging (optional but recommended)
				\Log::error("Error creating GPS: " . $e->getMessage());

				// Redirect back with input data and error message
				return redirect()->back()->withInput()->with('error', 'Error occurred while creating GPS.');
			}
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
		/**
		 * Search the specified resource from storage.
		 */
//		public function search($search, $searchField = false, $exactIdVeicolo = false) {
//			$result = Gps::search($search, $exactId, $exactIdVeicolo);
//			return response()->json($result);
//		}
	}
