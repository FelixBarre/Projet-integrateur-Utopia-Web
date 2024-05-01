<?php

namespace App\Http\Controllers;

use App\Models\CompteBancaire;
use App\Models\Transaction;
use App\Models\TypeTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();

        return view('accueil/accueil', [
            'employe'=>$employe,
            'transactions'=>Transaction::all(),
            'type_transactions'=>TypeTransaction::all(),
            'date_time'=>$date_time
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        $transaction = Transaction::find($id);
        $transactions = Transaction::where('id_compte_envoyeur', $id)->get();


        if($request->routeIs('transactions')){
            return view('transaction/transactions', [
                'employe'=>Auth::user(),
                'date_time'=>Carbon::now()->format('d-M-Y H:i'),
                'transaction'=> $transaction,
                'transactions'=>$transactions,
                'type_transactions'=>TypeTransaction::all()

            ]);

        }
        else if($request->routeIs('transaction')){
            return view('transaction/transaction', [
                'employe'=>Auth::user(),
                'date_time'=>Carbon::now()->format('d-M-Y H:i'),
                'transaction'=>$transaction,
                'type_transactions'=>TypeTransaction::all()

            ]);
        }


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
