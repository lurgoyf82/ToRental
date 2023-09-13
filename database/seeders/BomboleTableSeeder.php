<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bombole;

class BomboleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Bombole::truncate();

        // Get all id values from the dettaglio_veicolo table
        $veicoloIds = DB::table('dettaglio_veicolo')->pluck('id');

        // Loop to create and insert dummy records
        foreach ($veicoloIds as $veicoloId) {
            for($i=0;$i<3;$i++) {
                $anno = rand(2022, 2023); // Generate a random year
                $tipoScadenza = ['Quadrimestrale', 'Semestrale', 'Annuale'][rand(0, 2)]; // Choose a random tipo_scadenza

                // Calculate inizio_validita based on anno
                $inizioValidita = Carbon::createFromDate($anno, 1, 1);
                $inizioValidita->addDays(rand(0, 365));

                // Calculate data_pagamento as a random date between -7 and +7 days from inizio_validita
                $dataPagamento = $inizioValidita->copy()->addDays(rand(-7, 7));

                // Calculate fine_validita based on tipo_scadenza
                if ($tipoScadenza === 'Quadrimestrale') {
                    $fineValidita = $inizioValidita->copy()->addMonths(4);
                } elseif ($tipoScadenza === 'Semestrale') {
                    $fineValidita = $inizioValidita->copy()->addMonths(6);
                } else {
                    $fineValidita = $inizioValidita->copy()->addYear();
                }

                // Insert the data into the table
                DB::table('bombole')->insert([
                    'id_veicolo' => $veicoloId, // Assign the id_veicolo from dettaglio_veicolo
                    'anno' => $anno,
                    'data_pagamento' => $dataPagamento->toDateString(),
                    'inizio_validita' => $inizioValidita->toDateString(),
                    'fine_validita' => $fineValidita->toDateString(),
                    'importo' => rand(100, 10000), // Generate a random importo
                ]);

                // Increment inizio_validita for the next record to avoid overlap
                $inizioValidita->addDay();
            }
        }
    }
}
