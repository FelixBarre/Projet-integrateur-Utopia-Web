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
    Route::get('/profilesApi/{email}', 'getUserByEmail')->name('users.getUsersAPI');
    Route::get('/profileApi', 'show')->name('getUserApi')->middleware('auth:sanctum');
    Route::put('/modification/profileApi', 'updateApi')->name('updateUserApi');
});

Route::post('/token', [RegisteredUserController::class, 'show'])->name('token');

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
    Route::post('/virementApi/new', 'store')->name('newVirementApi');
    Route::post('/transactionApi/update', 'update')->name('updateTransactionApi');
    Route::post('/transactionApi/delete', 'update')->name('deleteTransactionApi');
    Route::get('/transactions/filter/{id_type_transaction}', 'show')->name('transactionsFilterApi');
});

Route::controller(DemandeController::class)->group(function(){
    Route::get('/demandes_de_pret', 'index')->name('demandesPretApi');
    Route::get('/demande_de_pret', 'show')->name('demandePretApi');
    Route::post('/creation/demande_de_pret', 'store')->name('creationDemandePretApi');
    Route::put('/modification/demande_de_pret', 'update')->name('modificationDemandePretApi');
    Route::post('/modification/demande_de_pret', 'update')->name('modificationDemandePretApi'); //fetch
    Route::delete('/annulation/demande_de_pret', 'destroy')->name('annulationDemandePretApi');
});

Route::controller(MessageController::class)->group(function() {
    Route::get('/messages/{id_conversation}/{id_dernier_message}', 'getNewMessages')->name('getNewMessages');
    Route::get('/messages/updated/{id_conversation}/{date_derniere_update}', 'getUpdatedMessages')->name('getUpdatedMessages');
    Route::post('/messages', 'store')->name('envoiMessage');
    Route::put('/messages/{id}', 'update')->name('modificationMessage');
    Route::delete('/messages/{id}', 'destroy')->name('suppressionMessage');
});

Route::controller(ConversationController::class)->group(function() {
    Route::get('/conversations/{id_user}', 'index')->name('conversationsApi');
    Route::post('/conversations/{id_user}', 'store')->name('creerConversationApi');
    Route::get('/conversation/{id}/{id_user}', 'show')->name('conversationApi');
    Route::delete('/conversation/{id}', 'destroy')->name('fermerConversationApi');
});

Route::controller(PretController::class)->group(function() {
    Route::get('/prets', 'index')->name('PretsApi');
    Route::get('/pret', 'show')->name('PretApi');
    Route::post('/creation/pret', 'store')->name('creationPretApi');
    Route::put('/modification/pret', 'update')->name('modificationPretApi');
    Route::delete('/desactivation/pret', 'destroy')->name('desactivationPretApi');
});

Route::controller(FactureController::class)->group(function() {
    Route::get('/facturesApi/{id_fournisseur}', 'index')->name('facturesApi')->middleware('auth:sanctum');
    Route::get('/factureApi/{id}', 'index')->name('factureApi')->middleware('auth:sanctum');
    Route::post('/factureApi/new', 'store')->name('newFactureApi')->middleware('auth:sanctum');
    Route::post('/factureApi/update', 'update')->name('updateFactureApi')->middleware('auth:sanctum');
    Route::post('/factureApi/delete', 'destroy')->name('deleteFactureApi')->middleware('auth:sanctum');
});

Route::controller(CreditController::class)->group(function() {
    Route::get('/credits', 'index')->name('CreditsApi');
    Route::get('/credit', 'show')->name('CreditApi');
    Route::post('/creation/credit', 'store')->name('creationCreditApi');
    Route::put('/modification/credit', 'update')->name('modificationCreditApi');
    Route::delete('/desactivation/credit', 'destroy')->name('desactivationCreditApi');
});
