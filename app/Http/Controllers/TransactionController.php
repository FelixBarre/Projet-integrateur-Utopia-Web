<?php

namespace App\Http\Controllers;

use App\Http\Resources\FournisseurResource;
use App\Models\CompteBancaire;
use App\Models\Transaction;
use App\Models\TypeTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionsResource;
use App\Models\Fournisseur;
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
                return response()->json(['ERREUR' => 'Aucune opération n\'est liée à ce compte'], 400);

            return TransactionResource::collection($transactions);
        }



        else if($request->routeIs('transactionApi')){


            if (empty($transaction))
                return response()->json(['ERREUR' => 'Aucune opération n\'est liée à ce compte'], 400);

            return new TransactionResource($transaction);
        }

        else if($request->routeIs('depots')){

            $transactions = Transaction::where('id_type_transaction', 1)->get();

            return view('accueil.accueil', [
                'employe'=>$employe,
                'date_time'=>$date_time,
                'transactions'=>$transactions,
                'type_transactions'=>TypeTransaction::all()

            ]);
        }

        else if($request->routeIs('retraits')){

            $transactions = Transaction::where('id_type_transaction', 2)->get();

            return view('accueil.accueil', [
                'employe'=>$employe,
                'date_time'=>$date_time,
                'transactions'=>$transactions,
                'type_transactions'=>TypeTransaction::all()

            ]);
        }

        else if($request->routeIs('virements')){

            $transactions = Transaction::where('id_type_transaction', 3)->get();

            return view('accueil.accueil', [
                'employe'=>$employe,
                'date_time'=>$date_time,
                'transactions'=>$transactions,
                'type_transactions'=>TypeTransaction::all()

            ]);
        }


        else{
            return view('accueil/accueil', [
                'employe'=>$employe,
                'transactions'=>Transaction::orderBy('created_at', 'desc')->take(10)->get(),
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
                'montant' => 'required|regex:/^\d+(?:\.\d{2})?$/',
                'id_compte_envoyeur' => 'required|regex:/^\d+$/',
                'id_compte_receveur' => 'required|regex:/^\d+$/',
                'id_type_transaction' => 'required|regex:/^\d+$/',
                'id_etat_transaction' => 'required|regex:/^\d+$/',
                'id_facture' => 'required|regex:/^\d+$/',

                ], [
                'montant.required' => 'Veuillez entrer un montant valide',
                'id_compte_envoyeur.required' => 'Veuillez un numero de compte valide',
                'id_compte_receveur.required' => 'Veuillez un numero de compte valide',
                'id_type_transaction.required' => 'Veuillez un numero valide',
                'id_etat_transaction.required' => 'Veuillez un numero valide',
                'id_facture.required' => 'Veuillez un numero valide',
                ]);
                if ($validation->fails()) {
                    return response()->json(['ERREUR' => $validation->errors()], 400);
                }

                $contenuDecode = $validation->validated();



                $etatTransaction = $contenuDecode['id_etat_transaction'];
                $compteEnvoyeur = $contenuDecode['id_compte_receveur'];
                $compteReceveur = $contenuDecode['id_compte_receveur'];
                $typeTransaction = $contenuDecode['id_type_transaction'];
                $idFacture= $contenuDecode['id_facture'];

                //Facture
                if($contenuDecode['id_type_transaction'] == 4){
                $compteReceveur= null;
                $compteEnvoyeur = $contenuDecode['id_compte_envoyeur'];
                $compte1 = CompteBancaire::find($compteEnvoyeur);
                $idFacture = $contenuDecode['id_facture'];
                }
                //Dépôt
                elseif($contenuDecode['id_type_transaction'] == 1){
                    $compteEnvoyeur = null;
                    $compteReceveur = $contenuDecode['id_compte_receveur'];
                    $compte2 = CompteBancaire::find($compteReceveur);
                    $idFacture=null;

                }
                //Rétrait
                elseif($contenuDecode['id_type_transaction'] == 2){
                    $compteReceveur= null;
                    $compteEnvoyeur = $contenuDecode['id_compte_envoyeur'];
                    $compte1 = CompteBancaire::find($compteEnvoyeur);
                    $idFacture = null;

                }
                //Virement
                elseif($contenuDecode['id_type_transaction'] == 3){
                    $compteEnvoyeur = $contenuDecode['id_compte_envoyeur'];
                    $compteReceveur = $contenuDecode['id_compte_receveur'];
                    $compte1 = CompteBancaire::find($contenuDecode['id_compte_envoyeur']);
                    $compte2 = CompteBancaire::find($contenuDecode['id_compte_receveur']);
                    $idFacture = null;

                }

                if($typeTransaction == 2 && $compte1->solde< $contenuDecode['montant'] && $etatTransaction !=2 || $typeTransaction == 3 && $compte1->solde < $contenuDecode['montant'] && $etatTransaction !=2 || $typeTransaction == 4 && $compte1->solde < $contenuDecode['montant']){
                    return response()->json(['ERREUR' => 'La transaction n\'a pas pu être effectuée. Votre solde est insuffisant'], 400);
                }

                try {

                    Transaction::create([
                    'montant' => $contenuDecode['montant'],
                    'id_compte_envoyeur' => $compteEnvoyeur,
                    'id_compte_receveur' => $compteReceveur,
                    'id_type_transaction' => $typeTransaction,
                    'id_etat_transaction' => $etatTransaction,
                    'id_facture' => $idFacture,
                    'created_at' => now(),
                    'updated_at' => now()
                    ]);

                    if($typeTransaction == 2 && $contenuDecode['id_etat_transaction'] != 2){
                        $compte1 = CompteBancaire::find($contenuDecode['id_compte_envoyeur']);
                        $compte1->solde -= $contenuDecode['montant'];
                        $compte1->save();

                    }elseif($typeTransaction == 4 && $contenuDecode['id_etat_transaction'] != 2){
                        $compte1 = CompteBancaire::find($contenuDecode['id_compte_envoyeur']);
                        $compte1->solde -= $contenuDecode['montant'];
                        $compte1->save();

                    }elseif($typeTransaction == 1 && $contenuDecode['id_etat_transaction'] != 2){
                        $compte2 = CompteBancaire::find($contenuDecode['id_compte_receveur']);
                        $compte2->solde += $contenuDecode['montant'];
                        $compte2->save();
                    }elseif($typeTransaction == 3 && $contenuDecode['id_etat_transaction'] != 2){
                        $compte1 = CompteBancaire::find($contenuDecode['id_compte_envoyeur']);
                        $compte2 = CompteBancaire::find($contenuDecode['id_compte_receveur']);
                        $compte1->solde -= $contenuDecode['montant'];
                        $compte1->save();
                        $compte2->solde += $contenuDecode['montant'];
                        $compte2->save();

                    }

                    return response()->json([
                        'SUCCES' => 'La transaction a été effectuée avec succès.',], 200);

                }catch (QueryException $erreur) {
                    report($erreur);
                    return response()->json(['ERREUR' => 'La transaction n\'a pas pu être effectuée.'], 400);
                }

        }
        elseif($request->routeIs('newVirementApi')){
            $validation = Validator::make($request->all(), [
                'montant' => 'required|regex:/^\d+(?:\.\d{2})?$/',

                'id_compte_envoyeur' => 'required|regex:/^\d+$/',
                'id_compte_receveur' => 'required|regex:/^\d+$/'

                ], [
                'montant.required' => 'Veuillez entrer un montant valide',
                'id_compte_envoyeur.required' => 'Veuillez un numero de compte valide',
                'id_compte_receveur.required' => 'Veuillez un numero de compte valide'

                ]);

                if ($validation->fails()) {
                    return response()->json(['ERREUR' => $validation->errors()], 400);
                }

                $contenuDecode = $validation->validated();

                $compteEnvoyeur = CompteBancaire::find($contenuDecode['id_compte_envoyeur']);
                $compteReceveur = CompteBancaire::find($contenuDecode['id_compte_receveur']);

                if($compteEnvoyeur){
                    $soldeCompteEnvoyeur = $compteEnvoyeur->solde;
                }else{
                    return response()->json(['ERREUR' => 'Le compte envoyeur n\'a pas été trouvé.'], 400);
                }



                if($soldeCompteEnvoyeur < $contenuDecode['montant']){
                    return response()->json(['ERREUR' => 'Le Virement n\'a pas pu être effectuée. Votre solde est insuffisant'], 400);
                }

                try{
                    Transaction::create([
                    'montant' => $contenuDecode['montant'],
                    'id_compte_envoyeur' => $contenuDecode['id_compte_envoyeur'],
                    'id_compte_receveur' => $contenuDecode['id_compte_receveur'],
                    'id_type_transaction' => 3,
                    'id_etat_transaction' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                    ]);

                    $compteEnvoyeur->solde -= $contenuDecode['montant'];
                    $compteReceveur->solde += $contenuDecode['montant'];
                    $compteEnvoyeur->save();
                    $compteReceveur->save();

                    return response()->json(['SUCCES' => 'Le virement a été effectuée avec succès.'], 200);

                }catch (QueryException $erreur) {

                    report($erreur->getMessage());
                    return response()->json(['ERREUR' => 'Le virement n\'a pas pu être effectuée. Un des comptes est introuvable.'], 400);
                }

        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id_type_transaction = null)
    {
        $id = $request['id_compte'];
        $transaction = User::find($id);
        $transactions = Transaction::where('id_compte_envoyeur', $id)->get();
        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();

        if($request->routeIs('transactionsApiAll')){
            $id = $request['id'];
            $transactions = Transaction::where('id_compte_envoyeur', $id)
                                        ->orwhere('id_compte_receveur', $id)
                                        ->get();

            if ($transactions->isEmpty())
                return response()->json(['ERREUR' => 'Aucune transaction enrégistrée'], 400);

            return TransactionsResource::collection($transactions);
        }


        if($request->routeIs('transactions')){
            $id = $request['id_compte_envoyeur'];
            $user = User::find($id);
            $idUser = $user->id;
            $compte = User::where('id', $idUser)->first();
            $transaction = Transaction::find($id);
            $transactions = Transaction::where('id_compte_envoyeur', $id)
                            ->orWhere('id_compte_receveur', $id)->orderBy('created_at', 'desc')->get();
            if(is_null($transaction)){
                return abort(404);
            }
            return view('transaction/transactions', [
                'employe'=>$employe,
                'date_time'=>Carbon::now()->format('d-M-Y H:i'),
                'compte'=>$compte,
                'transaction'=> $transaction,
                'transactions'=>$transactions,
                'id_compte_envoyeur'=>$id,
                'type_transactions'=>TypeTransaction::all()

            ]);

        }
        else if($request->routeIs('transaction')){
            $id = $request['id_transaction'];
            $transaction = Transaction::find($id);
            if(is_null($transaction)){
                return abort(404);
            }
            return view('transaction/transaction', [
                'employe'=>$employe,
                'date_time'=>$date_time,
                'transaction'=>$transaction,
                'type_transactions'=>TypeTransaction::all()

            ]);
        }

        else if($request->routeIs('transactionsFilterApi')){

            $idTransaction = $id_type_transaction;

            $employe = Auth::user();
            $transactions = Transaction::where('id_type_transaction', $idTransaction)->orderBy('created_at', 'desc')->get();
            //return view('accueil/accueil', [
            //    'employe'=>$employe,
            //    'transactions'=>$transactions,
            //    'type_transactions'=>TypeTransaction::all(),
            //    'date_time'=>$date_time
            //]);

            return response()->json(TransactionResource::collection($transactions));
        }
        else if($request->routeIs('transactionsFilterUser')){
            $idTransaction = $request['id_type_Transaction'];
            $transaction = Transaction::find($idTransaction);
            $id_compte= $request['id_compte_envoyeur'];
            $employe = Auth::user();
            $transactions = Transaction::where('id_type_transaction', $idTransaction)
                                ->where('id_compte_envoyeur', $id_compte)
                                ->orderBy('created_at', 'desc')
                                ->get();

            if(is_null($transaction)){
                return abort(404);
            }

            return view('transaction/transactions', [
                'employe'=>$employe,
                'transaction'=>$transaction,
                'transactions'=>$transactions,
                'id_compte_envoyeur'=>$id_compte,
                'type_transactions'=>TypeTransaction::all(),
                'date_time'=>$date_time
            ]);
        }
        else if($request->routeIs('transactionsFilterDate')){
            $idTransaction = $request['id_type_Transaction'];

            $dateDebut = $request['date_debut'];
            $dateFin = $request['date_fin'];


            if (empty($dateDebut)) {

                session()->flash('erreur', 'Vous devez choisir une date de début');
                return redirect(route('accueil'));
            }

            if (!empty($dateFin) && $dateFin < $dateDebut) {
                session()->flash('erreur', 'La date de fin doit être ultérieure à la date de début.');
                return redirect(route('accueil'));

            }
            $transactions = Transaction::whereDate('created_at', '>=', $dateDebut)
                            ->whereDate('created_at', '<=', $dateFin)
                            ->orderBy('created_at', 'desc')
                            ->get();

            $employe = Auth::user();
            return view('accueil/accueil', [
                'employe'=>$employe,
                'transactions'=>$transactions,
                'type_transactions'=>TypeTransaction::all(),
                'date_time'=>$date_time
            ]);
        }
        else if($request->routeIs('transactionsFilterDateUser')){
            $idTransaction = $request['id_type_Transaction'];

            $dateDebut = $request['date_debut'];
            $dateFin = $request['date_fin'];
            $transactions = Transaction::whereDate('created_at', '>=', $dateDebut)
                            ->whereDate('created_at', '<=', $dateFin)
                            ->orderBy('created_at', 'desc')
                            ->get();

            $employe = Auth::user();
            return view('accueil/accueil', [
                'employe'=>$employe,
                'transactions'=>$transactions,
                'type_transactions'=>TypeTransaction::all(),
                'date_time'=>$date_time
            ]);
        }

        else if($request->routeIs('transactionsFilterEmail')){

            $user = User::where('email', $request['email'])->first();

            if(!$user){

                session()->flash('erreur', 'Aucune transaction ne correspond à cette adresse.');
                return redirect(route('accueil'));

            }else{

                $idUser = $user->id;

                $transactions = Transaction::where('id_compte_envoyeur', $idUser)
                            ->orWhere('id_compte_receveur', $idUser)
                            ->orderBy('created_at', 'desc')
                            ->get();

                $employe = Auth::user();
                return view('accueil.filter', [
                    'employe'=>$employe,
                    'transactions'=>$transactions,
                    'type_transactions'=>TypeTransaction::all(),
                    'date_time'=>$date_time
                ]);

            }


        }

        else if($request->routeIs('transactionsFilter')){
            $montant = request('montant');
            $id = $request['id_compte_user'];

            $user = User::find($id);

            $idUser = $user->id;
            $compte = User::where('id', $idUser)->first();
            $transaction = Transaction::find($id);



            $transactions = Transaction::where('id_compte_envoyeur', $idUser)
            ->orWhere('id_compte_receveur', $idUser)
            ->where('montant', '=', $montant) // Condition sur le montant
            ->orderBy('created_at', 'desc')
            ->get();

            if(!$transactions){

                session()->flash('erreur', 'Aucune transaction ne correspond a ce montant.');
                return redirect(route('accueil'));

            }else{

                $employe = Auth::user();
                return view('transaction.filter', [
                    'employe'=>$employe,
                    'compte'=>$compte,
                    'transaction'=>$transaction,
                    'transactions'=>$transactions,
                    'type_transactions'=>TypeTransaction::all(),
                    'date_time'=>$date_time
                ]);


            }





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
        if($request->routeIs('updateTransactionApi')){

            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'montant' => 'required|regex:/^\d+(?:\.\d{2})?$/',
                'id_compte_envoyeur' => 'required|regex:/^\d+$/',
                'id_compte_receveur' => 'required|regex:/^\d+$/',
                'id_type_transaction' => 'required|regex:/^\d+$/',
                'id_etat_transaction' => 'required|regex:/^\d+$/'

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



                if (!Transaction::find($contenuDecode['id'])) {
                    return response()->json(['ERREUR' => 'Cette transaction n\'existe pas.'], 400);
                } elseif (!Transaction::where('id_etat_transaction', 2)
                                        ->orwhere('id_etat_transaction', 3)->find($contenuDecode['id'])) {
                    return response()->json(['ERREUR' => 'Cette transaction est déjà finaliser, vous ne pouver pas la modifier.'], 400);
                }

                $transaction = transaction::find($contenuDecode['id']);
                $transaction->montant = $contenuDecode['montant'];
                $transaction->id_compte_envoyeur = $contenuDecode['id_compte_envoyeur'];
                $transaction->id_compte_receveur = $contenuDecode['id_compte_receveur'];
                $transaction->id_type_transaction = $contenuDecode['id_type_transaction'];
                $transaction->id_etat_transaction = $contenuDecode['id_etat_transaction'];
                $transaction->updated_at = now();

                if ($transaction->save())
                    return response()->json(['SUCCES' => 'La modification de la transaction a été effectuée avec succès.'], 200);
                else
                    return response()->json(['ERREUR' => 'La modification de la transaction a échoué.'], 400);


        }else if ($request->routeIs('deleteTransactionAp')) {
            if (!Transaction::find($request['id'])) {
                return response()->json(['ERREUR' => 'Cette transaction n\'existe pas.'], 400);
            } elseif (Transaction::where('id_etat_transaction', 2)
                                ->orWhere('id_etat_transaction', 3)->find($request['id'])) {
                return response()->json(['ERREUR' => 'Cette transaction à déjà été Annuler.'], 400);
            }

            $transaction = Transaction::find($request['id']);
            $transaction->id_etat_transaction = 3;
            $transaction->updated_at = now();

            if ($transaction->save())
                return response()->json(['SUCCES' => 'La transaction a été annulée avec succès.'], 200);
            else
                return response()->json(['ERREUR' => 'L\annulation de la transaction a échoué.'], 400);

         }else if ($request->routeIs('deleteTransactionApi')) {
            if (!Transaction::find($request['id'])) {
                return response()->json(['ERREUR' => 'Cette transaction n\'existe pas.'], 400);
            } elseif (Transaction::where('id_etat_transaction', 2)
                                ->orWhere('id_etat_transaction', 3)->find($request['id'])) {

                return response()->json(['ERREUR' => 'Cette transaction à déjà été Annuler.'], 400);
            }

            $transaction = Transaction::find($request['id']);
            $transaction->id_etat_transaction = 3;
            $transaction->updated_at = now();

            if ($transaction->save())
                return response()->json(['SUCCES' => 'La transaction a été annulée avec succès.'], 200);
            else
                return response()->json(['ERREUR' => 'L\annulation de la transaction a échoué.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
