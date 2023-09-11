<?php

use App\Http\Controllers\NoleggioController;
use App\Http\Controllers\TachigrafoController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AtpController;
use App\Http\Controllers\BomboleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VeicoloController;
use App\Http\Controllers\LeasingController;
use App\Http\Controllers\AssicurazioneController;
use App\Http\Controllers\BolloController;
use App\Http\Controllers\RevisioneController;
use App\Http\Controllers\TagliandoController;
use App\Http\Controllers\SocietaController;
use App\Http\Controllers\ModelloController;


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
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //alert revisione meccanica
    Route::get('alert_revisione_meccanica', [RevisioneController::class, 'listExpiringRevisioniMeccaniche']);
    //alert revisione bombole
    Route::get('alert_revisione_bombole', [BomboleController::class, 'alert']);
    //alert revisione atp
    Route::get('alert_revisione_atp', [AtpController::class, 'alert']);
    //alert revisione tachigrafo
    Route::get('alert_revisione_tachigrafo', [TachigrafoController::class, 'alert']);
    //alert contratto noleggio
    Route::get('alert_contratto_noleggio', [NoleggioController::class, 'alert']);
    //alert polizza assicurativa
    Route::get('alert_polizza_assicurativa', [AssicurazioneController::class, 'alert']);
    //alert scadenza bollo
    Route::get('alert_scadenza_bollo', [BolloController::class, 'alert']);

    /*
        //alert leasing
        Route::get('alert_leasing', [LeasingController::class, 'alertList']);
        //alert tagliando
        Route::get('alert_tagliando', [TagliandoController::class, 'alertList']);
    */




    Route::resource('lista_societa', SocietaController::class);




    //Routes for CRUD veicolo
    Route::get('create_veicolo', [VeicoloController::class, 'create']);
    Route::get('list_veicolo', [VeicoloController::class, 'index']);
    Route::put('update_veicolo/{id}', [VeicoloController::class, 'update']);
    Route::delete('delete_veicolo/{id}', [VeicoloController::class, 'delete']);

    Route::get('get-modello-by-marca/{id}', [ModelloController::class, 'getModelloByMarca']);

    //route for assign_role to a user with Spatie
    Route::get('assign_role', [UserController::class, 'index']);

});

require __DIR__.'/auth.php';
