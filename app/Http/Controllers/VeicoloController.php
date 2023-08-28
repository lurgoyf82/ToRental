<?php

namespace App\Http\Controllers;

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



class VeicoloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('list_veicolo');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /*
         * id_proprietario
         * id_tipo_veicolo
         * id_tipo_allestimento
         * id_marca
         * id_modello
         * tipo_asse
         * tipo_cambio
         * alimentazione
         * destinazione_uso
         */



        //list to handle id_propietario
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
        $validatedData = $request->validate([
            // Add your validation rules here
        ]);

        Veicolo::create($validatedData);
        return redirect()->route('veicolo.index')->with('success', 'Veicolo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $veicolo = Veicolo::findOrFail($id);
        return view('veicolo.show', compact('veicolo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $veicolo = Veicolo::findOrFail($id);
        return view('veicolo.edit', compact('veicolo'));
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
        return redirect()->route('veicolo.index')->with('success', 'Veicolo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $veicolo = Veicolo::findOrFail($id);
        $veicolo->delete();
        return redirect()->route('veicolo.index')->with('success', 'Veicolo deleted successfully.');
    }
    public function checkRevisione()
    {
        $veicoli = Veicolo::all();

        foreach ($veicoli as $veicolo) {
            $revisione = $veicolo->revisione()->latest('fine_validita')->first();
            $today = Carbon::now();
            $alert = 'no alerts';

            if (!$revisione || $today->greaterThan($revisione->fine_validita)) {
                $alert = 'black';
            } elseif ($today->greaterThanOrEqualTo($revisione->inizio_validita) && $today->lessThanOrEqualTo($revisione->fine_validita)) {
                $daysToExpiration = $today->diffInDays($revisione->fine_validita);

                if ($daysToExpiration <= 6) {
                    $alert = 'red';
                } elseif ($daysToExpiration <= 29) {
                    $alert = 'yellow';
                } elseif ($daysToExpiration <= 59) {
                    $alert = 'green';
                }
            }

            // Send alert
            // ...
        }
    }
}
