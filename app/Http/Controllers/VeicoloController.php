<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Societa;
use App\Models\Veicolo;



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
        $lista_societa = Societa::all();
        return view('create_veicolo', ['lista_societa' => $lista_societa]);


        return view('create_veicolo');
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
