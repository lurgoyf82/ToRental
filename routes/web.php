<?php

use App\Http\Controllers\VeicoloController;
use App\Http\Controllers\LeasingController;
use App\Http\Controllers\AssicurazioneController;
use App\Http\Controllers\BolloController;
use App\Http\Controllers\RevisioneController;
use App\Http\Controllers\TagliandoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocietaController;
use App\Http\Controllers\ModelloController;
use App\Models\Societa;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //alert leasing
    Route::get('/alert_leasing', [LeasingController::class, 'alert']);
    //alert assicurazione
    Route::get('/alert_polizza_assicurativa', [AssicurazioneController::class, 'alert']);
    //alert bollo
    Route::get('/alert_scadenza_bollo', [BolloController::class, 'alert']);
    //alert revisione
    Route::get('/alert_revisione_meccanica', [RevisioneController::class, 'alert']);
    //alert tagliando
    Route::get('/alert_tagliando', [TagliandoController::class, 'alert']);


    Route::get('alert_revisione_bombole', [LeasingController::class, 'alert']);
    Route::get('alert_revisione_atp', [LeasingController::class, 'alert']);
    Route::get('alert_revisione_tachigrafo', [LeasingController::class, 'alert']);
    Route::get('alert_revisione_cronotachigrafo', [LeasingController::class, 'alert']);
    Route::get('alert_contratto_noleggio', [LeasingController::class, 'alert']);



    Route::resource('lista_societa', SocietaController::class);




    //Routes for CRUD veicolo
    Route::get('/create_veicolo', [VeicoloController::class, 'create']);
    Route::get('/list_veicolo', [VeicoloController::class, 'read']);
    Route::put('/update_veicolo/{id}', [VeicoloController::class, 'update']);
    Route::delete('/delete_veicolo/{id}', [VeicoloController::class, 'delete']);
    Route::get('/get-modello-by-marca/{id}', [ModelloController::class, 'getModelloByMarca']);


});

require __DIR__.'/auth.php';
