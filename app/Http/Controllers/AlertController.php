<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

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


    function addAlertLevels($records) {
        foreach ($records as $record) {
            $today = Carbon::now();
            $inizio_validita = Carbon::parse($record->revisione_inizio_validita);
            $fine_validita = Carbon::parse($record->revisione_fine_validita);

            if (is_null($fine_validita) || $fine_validita->lt($today)) {
                $record->revisione_alert = 'black';
            } elseif ($fine_validita->gt($today) && $inizio_validita->lte($today)) {
                $daysToExpiration = $today->diffInDays($fine_validita, false);

                if ($daysToExpiration > 60) {
                    $record->revisione_alert = 'no alerts';
                } elseif ($daysToExpiration >= 30 && $daysToExpiration <= 59) {
                    $record->revisione_alert = 'green';
                } elseif ($daysToExpiration >= 7 && $daysToExpiration <= 29) {
                    $record->revisione_alert = 'yellow';
                } elseif ($daysToExpiration >= 0 && $daysToExpiration <= 6) {
                    $record->revisione_alert = 'red';
                }
            }
        }

        return $records;
    }
}
