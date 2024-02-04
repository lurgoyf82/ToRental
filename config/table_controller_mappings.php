<?php

	use App\Http\Controllers\AssicurazioneController;

	return ['assicurazione' => App\Http\Controllers\AssicurazioneController::class,
					'atp' => App\Http\Controllers\AtpController::class,
					'bollo' => App\Http\Controllers\BolloController::class,
					'bombole' => App\Http\Controllers\BomboleController::class,
					'decorazione' => App\Http\Controllers\DecorazioneController::class,
					//'destinazione_uso' => App\Http\Controllers\DestinazioneUsoController::class,
					'veicolo' => App\Http\Controllers\DettaglioVeicoloController::class,
					'gps' => App\Http\Controllers\GpsController::class,
					//'leasing' => App\Http\Controllers\LeasingController::class,
					'marca' => App\Http\Controllers\MarcaController::class,
					'modello' => App\Http\Controllers\ModelloController::class,
					'multa' => App\Http\Controllers\MultaController::class,
					//'noleggio' => App\Http\Controllers\NoleggioController::class,
					'revisione' => App\Http\Controllers\RevisioneController::class,
					'societa' => App\Http\Controllers\SocietaController::class,
					'tachigrafo' => App\Http\Controllers\TachigrafoController::class,
					'tagliando' => App\Http\Controllers\TagliandoController::class,
					'targa' => App\Http\Controllers\TargaController::class,
					'telaio' => App\Http\Controllers\TelaioController::class
	];
