<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompteBancaireController;
use App\Http\Controllers\TransactionController;

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

Route::controller(TransactionController::class)->group(function(){
    Route::get('transactionsApi/{id}', 'index')->name('transactionsApi');
    Route::get('transactionApi/{id}', 'index')->name('transactionApi');
    Route::post('/transactionApi/new', 'store')->name('newTransactionApi');
    Route::post('/transactionApi/update', 'update')->name('updateTransactionApi');
    Route::post('/transactionApi/delete', 'update')->name('deleteTransactionApi');

});

