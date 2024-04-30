<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompteBancaireController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(CompteBancaireController::class)->group(function() {
    Route::get('/comptesBancaires', 'index')->name('comptesBancairesApi');
    Route::get('/compteBancaire', 'show')->name('compteBancaireApi');
    Route::post('/creation/compteBancaire', 'store')->name('creationCompteBancaireApi');
    Route::put('/modification/compteBancaire', 'update')->name('modificationCompteBancaireApi');
    Route::delete('/desactivation/compteBancaire', 'destroy')->name('desactivationCompteBancaireApi');
});
