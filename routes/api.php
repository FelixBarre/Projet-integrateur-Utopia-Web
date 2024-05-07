<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompteBancaireController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ProfileController::class)->group(function() {
    Route::get('/profilesApi/parCourriel', 'getUserByEmail')->name('users.getUsersAPI');
});

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

Route::controller(DemandeController::class)->group(function(){
    Route::get('/demandes_de_pret', 'index')->name('demandesPretApi');
    Route::get('/demande_de_pret', 'show')->name('demandePretApi');
    Route::post('/creation/demande_de_pret', 'store')->name('creationDemandePretApi');
    Route::put('/modification/demande_de_pret', 'update')->name('modificationDemandePretApi');
    Route::delete('/annulation/demande_de_pret', 'destroy')->name('annulationDemandePretApi');
});

Route::controller(MessageController::class)->group(function() {
    Route::get('/messages/{id_conversation}/{id_dernier_message}', 'getNewMessages')->name('getNewMessages');
    Route::get('/messages/updated/{id_conversation}/{date_derniere_update}', 'getUpdatedMessages')->name('getUpdatedMessages');
    Route::post('/messages', 'store')->name('envoiMessage');
    Route::put('/messages/{id}', 'update')->name('modificationMessage');
    Route::delete('/messages/{id}', 'destroy')->name('suppressionMessage');
});
