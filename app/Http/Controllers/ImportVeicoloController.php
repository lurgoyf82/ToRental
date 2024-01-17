<?php

namespace App\Http\Controllers;

use App\Models\ImportVeicolo;
use Illuminate\Http\Request;

class ImportVeicoloController extends Controller
{


	/****************** Standard web methods ***************/


	public function index() { /* Return view with all Imports */
		$import_veicolo = ImportVeicolo::all();
		return view('import_veicolo.index', compact('import_veicolo'));
	}


	public function create() { /* Return view to create a new post */
		return view('import_veicolo.create');
	}


	public function store(Request $request) { /* Store new Imported Data */
		$validatedData = ImportVeicolo::validate($request);
		ImportVeicolo::create($validatedData);

		return redirect()->route('import_veicolo.index');
	}
	public function show(ImportVeicolo $importVeicolo) { /* Return view with a single import_veicolo */
		return view('import_veicolo.show', compact('import_veicolo'));
	}
	public function edit(ImportVeicolo $importVeicolo) { /* Return view to edit a import_veicolo */
		return view('import_veicolo.edit', compact('import_veicolo'));
	}
	public function update(Request $request, ImportVeicolo $importVeicolo) { /* Update a import_veicolo */
		$validatedData = ImportVeicolo::validate($request);
		$importVeicolo->update($validatedData);

		return redirect()->route('import_veicolo.index');
	}
	public function destroy(ImportVeicolo $importVeicolo) { /* Delete a import_veicolo */
		$importVeicolo->delete();
		return redirect()->route('import_veicolo.index');
	}

	/****************** API methods ***************/
	public function apiIndex() {
		/* Return all import_veicolo as JSON for API calls */
		$import_veicolo = ImportVeicolo::all();
		return $import_veicolo->toJson();
	}


	public function apiShow(ImportVeicolo $importVeicolo) {
		/* Return a single ImportVeicolo as JSON */
		return $importVeicolo->toJson();
	}
	// ... other API-specific methods

}
