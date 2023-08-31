<?php

namespace App\Http\Controllers;

use App\Models\Revisione;
use App\Models\Targa;
use Illuminate\Http\Request;

class RevisioneController extends Controller
{
    //controller for alert leasing
    public function alertList(Revisione $revisione, Request $request=null)
    {
        $alertList = $revisione::getRevisioneAlertList($request);

        $targaList= Targa::getTargaListByIdVeicolo();
        foreach ($alertList as $key=>$alert) {
            if(isset($targaList[$alert->id_veicolo])) {
                $alertList[$key]->targa = $targaList[$alert->id_veicolo]->targa;
            }
        }
        return view('alert_revisione_meccanica', ['alertList' => $alertList]);
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
}
