<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\DemandeController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsEmploye;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::controller(ProfileController::class)->group(function() {
    Route::get('profile',  'show')->name('profile.show');
    Route::get('profile/edit', 'edit')->name('profile.edit');
});

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
})->middleware(EnsureUserIsEmploye::class);

Route::controller(DemandeController::class)->group(function(){
    Route::get('demandes_de_pret', 'index')->name('demandesPret');
    Route::get('demandes_de_pret/filtre', 'index')->name('demandesPretFiltre');
});

require __DIR__.'/auth.php';
