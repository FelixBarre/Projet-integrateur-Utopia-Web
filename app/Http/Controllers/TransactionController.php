<?php

namespace App\Http\Controllers;

use App\Models\CompteBancaire;
use App\Models\Transaction;
use App\Models\TypeTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();
        $transaction = Transaction::find($request['id']);
        $transactions = Transaction::where('id_compte_envoyeur',$request['id'])->get();

        if($request->routeIs('transactionsApi')){

            if ($transactions->isEmpty())
                return response()->json(['ERREUR' => 'Aucune transaction n\'est liée à ce compte'], 400);

            return TransactionResource::collection($transactions);
        }
        else if($request->routeIs('transactionApi')){


            if (empty($transaction))
                return response()->json(['ERREUR' => 'Aucune transaction n\'est liée à ce compte'], 400);

            return new TransactionResource($transaction);
        }
        else{
            return view('accueil/accueil', [
                'employe'=>$employe,
                'transactions'=>Transaction::all(),
                'type_transactions'=>TypeTransaction::all(),
                'date_time'=>$date_time
            ]);


        }






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
        if($request->routeIs('newTransactionApi')){
            $validation = Validator::make($request->all(), [
                'montant' => 'required|regex:/^\d+\.\d\d$/',
                'id_compte_envoyeur' => 'required|regex:/^[1-9]\d*$/',
                'id_compte_receveur' => 'required|regex:/^[1-9]\d*$/',
                'id_type_transaction' => 'required|regex:/^[1-9]\d*$/',
                'id_etat_transaction' => 'required|regex:/^[1-9]\d*$/',
                ], [
                'montant.required' => 'Veuillez entrer un chiffre valide',
                'id_compte_envoyeur.required' => 'Veuillez un numero de compte valide',
                'id_compte_receveur.required' => 'Veuillez un numero de compte valide',
                'id_type_transaction.required' => 'Veuillez entrer un chiffre validee',
                'id_etat_transaction.required' => 'Veuillez entrer un chiffre valide',

                ]);
                if ($validation->fails()) {
                    return response()->json(['ERREUR' => $validation->errors()], 400);
                }

                $contenuDecode = $validation->validated();

                try {
                    Transaction::create([
                    'montant' => $contenuDecode['montant'],
                    'id_compte_envoyeur' => $contenuDecode['id_compte_envoyeur'],
                    'id_compte_receveur' => $contenuDecode['id_compte_receveur'],
                    'id_type_transaction' => $contenuDecode['id_type_transaction'],
                    'id_etat_transaction' => $contenuDecode['id_etat_transaction'],
                    'created_at' => now(),
                    'updated_at' => null
                    ]);
                    } catch (QueryException $erreur) {
                        report($erreur);
                    return response()->json(['ERREUR' => 'La transaction n\'a pas pu être effectuée.'], 500);
                    }

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        $transaction = Transaction::find($id);
        $transactions = Transaction::where('id_compte_envoyeur', $id)->get();


        if($request->routeIs('transactions')){
            if(is_null($transaction)){
                return abort(404);
            }
            return view('transaction/transactions', [
                'employe'=>Auth::user(),
                'date_time'=>Carbon::now()->format('d-M-Y H:i'),
                'transaction'=> $transaction,
                'transactions'=>$transactions,
                'type_transactions'=>TypeTransaction::all()

            ]);

        }
        else if($request->routeIs('transaction')){
            if(is_null($transaction)){
                return abort(404);
            }
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
