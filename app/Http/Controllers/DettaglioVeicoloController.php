<?php


	namespace App\Http\Controllers;

	use App\Models\DettaglioVeicolo;
	use App\Models\Targa;
	use Illuminate\Http\Request;
	use Carbon\Carbon;
	use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
	use Illuminate\Foundation\Validation\ValidatesRequests;
	use Illuminate\Routing\Controller as BaseController;
	use App\Models\Societa;
	use App\Models\Veicolo;
	use App\Models\TipoVeicolo;
	use App\Models\TipoAllestimento;
	use App\Models\Marca;
	use App\Models\Modello;
	use App\Models\TipoAsse;
	use App\Models\TipoCambio;
	use App\Models\TipoAlimentazione;
	use App\Models\DestinazioneUso;
	use Illuminate\Support\Arr;

	class DettaglioVeicoloController extends Controller
	{
		public function __construct()
		{
			$this->model = DettaglioVeicolo::class;
		}
		/**
		 * Display a listing of the resource.
		 */
//
//		public function index2(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
//		{
//			$search = $request->input('search',null);
//			$order  = $request->input('order','livello');
//			$page   = $request->input('page', 1);  // default to 1 if not provided
//
//
//			$expiringRevisioniMeccaniche = DettaglioVeicolo::index($search, $order, $page);
//
//			$targaList= Targa::getTargaListByIdVeicolo();
//			foreach ($expiringRevisioniMeccaniche as $key=>$alert) {
//				if(isset($targaList[$alert->id_veicolo])) {
//					$expiringRevisioniMeccaniche[$key]->targa = $targaList[$alert->id_veicolo]->targa;
//				}
//			}
//			return view('list_veicolo', ['expiringRevisioniMeccaniche' => $expiringRevisioniMeccaniche]);
//		}


		public function list_veicolo(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			// 'list_bollo' is the view name specific to this controller
			return $this->indexView($request, DettaglioVeicolo::class, 'list_veicolo');
		}

		public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
		{
			return $this->indexView($request, DettaglioVeicolo::class, 'list_veicolo');
		}


		/**
		 * Show the form for creating a new resource.
		 */
		public function create()
		{
			//list to handle id_proprietario
			$lista_societa = Societa::orderBy('nome')->get();
			//list to handle id_tipo_veicolo
			$lista_tipo_veicolo = TipoVeicolo::orderBy('nome')->get();
			//list to handle id_tipo_allestimento
			$lista_tipo_allestimento = TipoAllestimento::orderBy('nome')->get();
			//list to handle id_marca
			$lista_marca = Marca::orderBy('nome')->get();
			//list to handle id_modello
			$lista_modello = Modello::orderBy('nome')->get();
			//list to handle tipo_asse
			$lista_tipo_asse = TipoAsse::orderBy('nome')->get();
			//list to handle tipo_cambio
			$lista_tipo_cambio = TipoCambio::orderBy('nome')->get();
			//list to handle alimentazione
			$lista_alimentazione = TipoAlimentazione::orderBy('nome')->get();
			//list to handle destinazione_uso
			$lista_destinazione_uso = DestinazioneUso::orderBy('nome')->get();

			return view('create_veicolo', ['lista_societa' => $lista_societa,
				'lista_tipo_veicolo' => $lista_tipo_veicolo,
				'lista_tipo_allestimento' => $lista_tipo_allestimento,
				'lista_marca' => $lista_marca,
				'lista_modello' => $lista_modello,
				'lista_tipo_asse' => $lista_tipo_asse,
				'lista_tipo_cambio' => $lista_tipo_cambio,
				'lista_alimentazione' => $lista_alimentazione,
				'lista_destinazione_uso' => $lista_destinazione_uso]);
		}

		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request)
		{
			$validatedData = DettaglioVeicolo::validatePartial($request->all());

			// Remove 'targa' from $validatedData and create Veicolo
			$veicoloData = Arr::except($validatedData, ['targa', 'data_immatricolazione']);
			$veicolo = Veicolo::create($veicoloData);

			// Now create Targa with the veicolo ID and the targa value
			$targaData = [
				'id_veicolo' => $veicolo->id, // Use the ID of the Veicolo you just created
				'targa' => $validatedData['targa'], // Get the targa value from the original data
				'data_immatricolazione' => $validatedData['data_immatricolazione'] // Set the data_immatricolazione to today's date
			];

			Targa::create($targaData);

			return redirect()->route('create_veicolo')->with('success', 'Veicolo created successfully.');
		}

		/**
		 * Display the specified resource.
		 */
		public function show($id)
		{
			$veicolo = DettaglioVeicolo::findOrFail($id);
			return view('update_veicolo', compact('veicolo'));
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit($id)
		{
			$veicolo = DettaglioVeicolo::findOrFail($id);
			return view('edit_veicolo', compact('veicolo'));
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, $id)
		{
			$validatedData = $request->validate([
				// Add your validation rules here
			]);

			$veicolo = Veicolo::findOrFail($id);
			$veicolo->update($validatedData);
			return redirect()->route('list_veicolo')->with('success', 'Veicolo updated successfully.');
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy($id)
		{
			$veicolo = Veicolo::findOrFail($id);
			$veicolo->delete();
			return redirect()->route('list_veicolo')->with('success', 'Veicolo deleted successfully.');
		}

		/**
		 * Search the specified resource from storage.
		 * @param Request $request
		 * @param false $search
		 * @param false $searchField
		 */


//		public function search(Request $request, $searchField = false)
//		{
//			$search = $request->input('search', null);
//			$result = DettaglioVeicolo::search($search, $searchField, $searchField);
//			return response()->json($result);
//		}

//	public function search($search, $search = false, $searchField = false)
//	{
//		var_dump($searchField, $searchFieldVeicolo);
//		$result = DettaglioVeicolo::search($search, $searchField, $searchField);
//		return response()->json($result);
//	}
//	}

	}
	//
//	namespace App\Http\Controllers;
//
//	use App\Models\DettaglioVeicolo;
//	use Illuminate\Http\Request;
//
//	class DettaglioVeicoloController extends Controller
//	{
//		/**
//		 * Display a listing of the resource.
//		 */
//		public function index()
//		{
//			$veicoli = DettaglioVeicolo::all();
//			return view('dettaglio_veicolo.index', compact('veicoli'));
//		}
//		/**
//		 * Show the form for editing the specified resource.
//		 */
//		public function edit(DettaglioVeicolo $dettaglioVeicolo)
//		{
//			// The $dettaglioVeicolo is automatically injected by Laravel
//			return view('dettaglio_veicolo.edit', compact('dettaglioVeicolo'));
//		}
//		// Implicit route model binding is being used here
//		public function update(Request $request, DettaglioVeicolo $dettaglioVeicolo)
//		{
//			$validatedData = DettaglioVeicolo::validate($request);
//			$dettaglioVeicolo->update($validatedData);
//
//			return redirect()->route('dettaglio_veicolo.index'); // Assuming this is the correct named route
//		}
//		/**
//		 * Remove the specified resource from storage.
//		 */
//		public function destroy(DettaglioVeicolo $dettaglioVeicolo)
//		{
//			// The $dettaglioVeicolo is automatically injected and ready to be deleted
//			$dettaglioVeicolo->delete();
//
//			return redirect()->route('dettaglio_veicolo.index'); // Assuming this is the correct named route
//		}
//
//		/**
//		 * Show the form for creating a new resource.
//		 */
//		public function create()
//		{
//			return view('dettaglio_veicolo.create');
//		}
//
//		/**
//		 * Store a newly created resource in storage.
//		 */
//		public function store(Request $request)
//		{
//			// Validation could be handled here or in a FormRequest
//			$validatedData = DettaglioVeicolo::validate($request);
//
//
//			$dettaglioVeicolo = DettaglioVeicolo::create($validatedData);
//
//			return redirect()->route('dettaglio_veicolo.show', $dettaglioVeicolo);
//		}
//
//		/**
//		 * Display the specified resource.
//		 */
//		public function show(DettaglioVeicolo $dettaglioVeicolo)
//		{
//			//
//		}
//
//
//		/**
//		 * Update the specified resource in storage.
//		 */
//
//	}


