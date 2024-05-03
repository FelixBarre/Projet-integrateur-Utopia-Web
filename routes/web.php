<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\DemandeController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsEmploye;
use App\Http\Middleware\EnsureUserIsNotUtilisateur;
use App\Models\Transaction;
use App\Models\TypeTransaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    if (Auth::check()) {
        {{ return redirect()->route('accueil'); }}
    }
    else
        return view('auth.login');
});

Route::middleware(['auth', EnsureUserIsNotUtilisateur::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/mdp/changement', [PasswordController::class, 'show'])->name('password.change');

    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::controller(TransactionController::class)->group(function(){
        Route::get('transactions/{id_compte_envoyeur}', 'show')->name('transactions');
        Route::get('transaction/{id_transaction}', 'show')->name('transaction');
        Route::get('accueil', 'index')->middleware(['auth', 'verified'])->name('accueil');
        Route::get('transaction/view/{id}', 'show')->name('transactionView');
        Route::post('transactions/filter', 'show')->name('transactionsFilter');
    });

    Route::controller(RapportController::class)->group(function(){
        Route::get('rapports', 'index')->name('rapports');
        Route::get('nouveauRapport', 'create')->name('nouveauRapport');
        Route::post('creationRapport', 'store')->name('creationRapport');
    });

    Route::controller(DemandeController::class)->group(function(){
        Route::get('demandesDePret', 'index')->name('demandesPret');
        Route::get('demandesDePret/filtre', 'index')->name('demandesPretFiltre');
    });

    Route::controller(ConversationController::class)->group(function(){
        Route::get('conversations', 'index')->name('conversations');
    });
});

require __DIR__.'/auth.php';
