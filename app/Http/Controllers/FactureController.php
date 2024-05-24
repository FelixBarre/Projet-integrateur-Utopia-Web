<?php

namespace App\Http\Controllers;

use App\Http\Resources\FactureResource;
use App\Models\Facture;
use App\Models\Fournisseur;
use App\Models\Transaction;
use App\Models\CompteBancaire;
use App\Models\TypeTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();
        $factures = Facture::where('id_fournisseur', $request['id_fournisseur'])->get(); //toutes les factures d'un fournisseur
        $facture = Facture::find($request['id']); //une facture en particulier

        if($request->routeIs('facturesApi')){

            if ($factures->isEmpty())
                return response()->json(['ERREUR' => 'Aucune facture n\'est liée à ce fournisseur'], 400);

            return FactureResource::collection($factures);
        }
        else if($request->routeIs('factures')){

            $factures = Facture::all();
            print('facture');

            return view('facture.factures', [
                'date_time'=>$date_time,
                'employe'=>$employe,
                'factures'=>$factures,
                'type_transactions'=>TypeTransaction::all()

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

        if($request->routeIs('newFactureApi')){
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
            $compteReceveur = null;
            $typeTransaction = $contenuDecode['id_type_transaction'];
            $idFacture= $contenuDecode['id_facture'];



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

                    $compte1 = CompteBancaire::find($contenuDecode['id_compte_envoyeur']);
                    $compte1->solde -= $contenuDecode['montant'];
                    $compte1->save();

                    return response()->json(['SUCCES' => 'La facture a été effectuée avec succès.'], 200);

                }catch (QueryException $erreur) {
                    report($erreur);
                    return response()->json(['ERREUR' => 'La transaction n\'a pas pu être effectuée.'], 400);
                }

            }

    }

    /**
     * Display the specified resource.
     */
    public function show(Facture $facture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facture $facture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required|regex:/^\d+$/',
            'nom' => 'required',
            'description' => 'required',
            'montant_defini' => 'required|regex:/^\d+(?:\.\d{2})?$/',
            'jour_du_mois' => 'required|regex:/^\d+$/',
            'id_fournisseur' => 'required||regex:/^\d+$/'

            ], [
                'id.required'=>'Veuillez entrer un id valide',
                'nom.required' => 'Veuillez entrer un nom valide',
                'description.required' => 'Veuillez entrer une description valide',
                'montant_defini.required' => 'Veuillez entrer un montant valide',
                'jour_du_mois.required' => 'Veuillez un jour valide',
                'id_fournisseur.required' => 'Veuillez un numero de compte valide',

            ]);
            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDecode = $validation->validated();

            if (!Facture::find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Cette facture n\'existe pas.'], 400);
            } elseif ($contenuDecode['jour_du_mois']<1 || $contenuDecode['jour_du_mois']>30) {
                return response()->json(['ERREUR' => 'le jour du mois n\'est pas valide.'], 400);
            }elseif(!Fournisseur::find($request['id_fournisseur'])){
                return response()->json(['ERREUR' => 'le fournisseur n\'a pas été trouvé.'], 400);
            }

            $facture = Facture::find($contenuDecode['id']);
            $facture->nom = $contenuDecode['nom'];
            $facture->description = $contenuDecode['description'];
            $facture->montant_defini = $contenuDecode['montant_defini'];
            $facture->jour_du_mois = $contenuDecode['jour_du_mois'];
            $facture->id_fournisseur = $contenuDecode['id_fournisseur'];


            if ($facture->save())
                return response()->json(['SUCCES' => 'La modification de la facture a été effectuée avec succès.'], 200);
            else
                return response()->json(['ERREUR' => 'La modification de la facture a échoué.'], 400);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $idFacture = $request['id_facture'];
        $facture = Facture::find($request['id_facture']);

        if(!$facture){

            return response()->json(['ERREUR' => 'La facture n\'a pas été trouvée.'], 400);

        }else{

            try{
                if(Facture::destroy($idFacture)){
                    return response()->json(['SUCCES' => 'La facture a été supprimée avec succès.'], 200);
                }

            }catch(QueryException $erreur){
                report($erreur);
                return response()->json(['ERREUR' => 'La facture n\'a été supprimée.'], 400);
            }
        }

    }
}
