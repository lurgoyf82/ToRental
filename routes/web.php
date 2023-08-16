<?php

use App\Http\Controllers\veicoloController;
use App\Http\Controllers\LeasingController;
use App\Http\Controllers\AssicurazioneController;
use App\Http\Controllers\BolloController;
use App\Http\Controllers\RevisioneController;
use App\Http\Controllers\TagliandoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/alert_assicurazione', [AssicurazioneController::class, 'alert']);
    //alert bollo
    Route::get('/alert_bollo', [BolloController::class, 'alert']);
    //alert revisione
    Route::get('/alert_revisione', [RevisioneController::class, 'alert']);
    //alert tagliando
    Route::get('/alert_tagliando', [TagliandoController::class, 'alert']);




    //Routes for CRUD veicolo
    Route::get('/create_veicolo', [VeicoloController::class, 'create']);
    Route::get('/list_veicolo', [VeicoloController::class, 'read']);
    Route::put('/update_veicolo/{id}', [VeicoloController::class, 'update']);
    Route::delete('/delete_veicolo/{id}', [VeicoloController::class, 'delete']);

});

require __DIR__.'/auth.php';
