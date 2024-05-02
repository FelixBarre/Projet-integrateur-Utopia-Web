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

Route::get('/', function () {
    if (Auth::check()) {
        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();

        return view('accueil/accueil', [
            'employe'=>$employe,
            'transactions'=>Transaction::all(),
            'type_transactions'=>TypeTransaction::all(),
            'date_time'=>$date_time
        ]);
    }
    else
        return view('auth.login');
});

/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(ProfileController::class)->group(function() {
    Route::get('/profile',  'show')->name('profile.show');
    Route::patch('/profile', 'edit')->name('profile.edit');
});

Route::controller(TransactionController::class)->group(function(){
    Route::get('accueil', 'index')->middleware(['auth', 'verified'])->name('accueil');
    Route::get('transaction/view/{id_compte_envoyeur}', 'show')->name('transactionView');
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
