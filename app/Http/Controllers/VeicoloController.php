<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return response()->json([
            'success' => true,
            'message' => 'List Veicolo',
            'data' => Veicolo::all()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function read()
    {
        return view('list_veicolo');
    }

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
     * Show the form for creating a new resource.
     */
    public function list()
    {
        //returns a list of all the veicoli
        return response()->json([
            'success' => true,
            'message' => 'List Veicolo',
            'data' => Veicolo::all()
        ], 200);
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
}
