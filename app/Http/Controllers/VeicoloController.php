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
use Illuminate\Support\Facades\DB;





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


            $query = DB::table('dettaglio_veicolo')
                ->leftJoin('revisione', function ($join) {
                    $join->on('revisione.veicolo_id', '=', 'dettaglio_veicolo.id')
                        ->where('revisione.inizio_validita', '<', now())
                        ->where('revisione.fine_validita', '>', now());
                })
                ->leftJoin('bollo', function ($join) {
                    $join->on('bollo.veicolo_id', '=', 'dettaglio_veicolo.id')
                        ->where('bollo.inizio_validita', '<', now())
                        ->where('bollo.fine_validita', '>', now());
                })
                ->leftJoin('bombole', function ($join) {
                    $join->on('bombole.veicolo_id', '=', 'dettaglio_veicolo.id')
                        ->where('bombole.inizio_validita', '<', now())
                        ->where('bombole.fine_validita', '>', now());
                })
                ->leftJoin('cronotachigrafo', function ($join) {
                    $join->on('cronotachigrafo.veicolo_id', '=', 'dettaglio_veicolo.id')
                        ->where('cronotachigrafo.inizio_validita', '<', now())
                        ->where('cronotachigrafo.fine_validita', '>', now());
                })
                ->leftJoin('tachigrafo', function ($join) {
                    $join->on('tachigrafo.veicolo_id', '=', 'dettaglio_veicolo.id')
                        ->where('tachigrafo.inizio_validita', '<', now())
                        ->where('tachigrafo.fine_validita', '>', now());
                })
                ->select([
                    'revisione.inizio_validita as revisione_inizio_validita',
                    'revisione.fine_validita as revisione_fine_validita',
                    'dettaglio_veicolo.id',
                    'tachigrafo.inizio_validita as tachigrafo_inizio_validita',
                    'tachigrafo.fine_validita as tachigrafo_fine_validita',
                    'bollo.inizio_validita as bollo_inizio_validita',
                    'bollo.fine_validita as bollo_fine_validita',
                    'bombole.inizio_validita as bombole_inizio_validita',
                    'bombole.fine_validita as bombole_fine_validita',
                    'cronotachigrafo.inizio_validita as cronotachigrafo_inizio_validita',
                    'cronotachigrafo.fine_validita as cronotachigrafo_fine_validita'
                ])
                ->orderBy('dettaglio_veicolo.id', 'ASC')
                ->get();

        }
    }
}
