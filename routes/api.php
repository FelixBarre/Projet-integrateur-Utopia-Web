<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompteBancaireController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PretController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ProfileController::class)->group(function() {
    Route::get('/profilesApi/{email}', 'getUserByEmail')->name('users.getUsersAPI')->middleware('auth:sanctum');
});

Route::post('/token', [RegisteredUserController::class, 'show'])->name('token');

Route::controller(CompteBancaireController::class)->group(function() {
    Route::get('/comptesBancaires', 'index')->name('comptesBancairesApi')->middleware('auth:sanctum');
    Route::get('/compteBancaire', 'show')->name('compteBancaireApi')->middleware('auth:sanctum');
    Route::post('/creation/compteBancaire', 'store')->name('creationCompteBancaireApi')->middleware('auth:sanctum');
    Route::put('/modification/compteBancaire', 'update')->name('modificationCompteBancaireApi')->middleware('auth:sanctum');
    Route::delete('/desactivation/compteBancaire', 'destroy')->name('desactivationCompteBancaireApi')->middleware('auth:sanctum');
});

Route::controller(TransactionController::class)->group(function(){
    Route::get('transactionsApi/{id}', 'index')->name('transactionsApi')->middleware('auth:sanctum');
    Route::get('transactionApi/{id}', 'index')->name('transactionApi')->middleware('auth:sanctum');
    Route::post('/transactionApi/new', 'store')->name('newTransactionApi')->middleware('auth:sanctum');
    Route::post('/virementApi/new', 'store')->name('newVirementApi')->middleware('auth:sanctum');
    Route::post('/transactionApi/update', 'update')->name('updateTransactionApi')->middleware('auth:sanctum');
    Route::post('/transactionApi/delete', 'update')->name('deleteTransactionApi')->middleware('auth:sanctum');
    Route::get('/transactions/filter/{id_type_transaction}', 'show')->name('transactionsFilterApi')->middleware('auth:sanctum');
});

Route::controller(DemandeController::class)->group(function(){
    Route::get('/demandes_de_pret', 'index')->name('demandesPretApi')->middleware('auth:sanctum');
    Route::get('/demande_de_pret', 'show')->name('demandePretApi')->middleware('auth:sanctum');
    Route::post('/creation/demande_de_pret', 'store')->name('creationDemandePretApi')->middleware('auth:sanctum');
    Route::put('/modification/demande_de_pret', 'update')->name('modificationDemandePretApi')->middleware('auth:sanctum');
    Route::post('/modification/demande_de_pret', 'update')->name('modificationDemandePretApi')->middleware('auth:sanctum'); //fetch
    Route::delete('/annulation/demande_de_pret', 'destroy')->name('annulationDemandePretApi')->middleware('auth:sanctum');
});

Route::controller(MessageController::class)->group(function() {
    Route::get('/messages/{id_conversation}/{id_dernier_message}', 'getNewMessages')->name('getNewMessages')->middleware('auth:sanctum');
    Route::get('/messages/updated/{id_conversation}/{date_derniere_update}', 'getUpdatedMessages')->name('getUpdatedMessages')->middleware('auth:sanctum');
    Route::post('/messages', 'store')->name('envoiMessage')->middleware('auth:sanctum');
    Route::put('/messages/{id}', 'update')->name('modificationMessage')->middleware('auth:sanctum');
    Route::delete('/messages/{id}', 'destroy')->name('suppressionMessage')->middleware('auth:sanctum');
});

Route::controller(ConversationController::class)->group(function() {
    Route::get('/conversations/{id_user}', 'index')->name('conversationsApi')->middleware('auth:sanctum');
    Route::post('/conversations/{id_user}', 'store')->name('creerConversationApi')->middleware('auth:sanctum');
    Route::get('/conversation/{id}/{id_user}', 'show')->name('conversationApi')->middleware('auth:sanctum');
    Route::delete('/conversation/{id}', 'destroy')->name('fermerConversationApi')->middleware('auth:sanctum');
});

Route::controller(PretController::class)->group(function() {
    Route::get('/prets', 'index')->name('PretsApi')->middleware('auth:sanctum');
    Route::get('/pret', 'show')->name('PretApi')->middleware('auth:sanctum');
    Route::post('/creation/pret', 'store')->name('creationPretApi')->middleware('auth:sanctum');
    Route::put('/modification/pret', 'update')->name('modificationPretApi')->middleware('auth:sanctum');
    Route::delete('/desactivation/pret', 'destroy')->name('desactivationPretApi')->middleware('auth:sanctum');
});

Route::controller(FactureController::class)->group(function() {
    Route::get('/facturesApi/{id_fournisseur}', 'index')->name('facturesApi')->middleware('auth:sanctum');
    Route::get('/factureApi/{id}', 'index')->name('factureApi')->middleware('auth:sanctum');
    Route::post('/factureApi/new', 'store')->name('newFactureApi')->middleware('auth:sanctum');
    Route::post('/factureApi/update', 'update')->name('updateFactureApi')->middleware('auth:sanctum');
    Route::post('/factureApi/delete', 'destroy')->name('deleteFactureApi')->middleware('auth:sanctum');
});

Route::controller(CreditController::class)->group(function() {
    Route::get('/credits', 'index')->name('CreditsApi')->middleware('auth:sanctum');
    Route::get('/credit', 'show')->name('CreditApi')->middleware('auth:sanctum');
    Route::post('/creation/credit', 'store')->name('creationCreditApi')->middleware('auth:sanctum');
    Route::put('/modification/credit', 'update')->name('modificationCreditApi')->middleware('auth:sanctum');
    Route::delete('/desactivation/credit', 'destroy')->name('desactivationCreditApi')->middleware('auth:sanctum');
});
