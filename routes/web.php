<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\DemandeController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsEmploye;
use App\Models\Transaction;

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
});

Route::controller(TransactionController::class)->group(function(){
    Route::get('accueil', 'index')->name('accueil');
    Route::get('transaction/view/{id_compte_envoyeur}', 'show')->name('transactionView');
});

Route::controller(RapportController::class)->group(function(){
    Route::get('rapports', 'index')->name('rapports');
    Route::get('nouveauRapport', 'create')->name('nouveauRapport');
    Route::get('creationRapport', 'store')->name('creationRapport');
})->middleware(EnsureUserIsEmploye::class);

Route::controller(DemandeController::class)->group(function(){
    Route::get('demandes_de_pret', 'index')->name('demandesPret');
    Route::get('demandes_de_pret/filtre', 'index')->name('demandesPretFiltre');
});

require __DIR__.'/auth.php';
