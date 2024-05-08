<?php

namespace App\Http\Controllers;

use App\Models\Pret;
use App\Models\Demande;
use App\Models\CompteBancaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PretResource;

class PretController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->routeIs('PretsApi')) {
            if(isset($request['id_user']) && User::find($request['id_user'])) {
                $pretArray = array();
                $comptesBancaires = CompteBancaire::where('id_user', $request['id_user'])->get();

                foreach ($comptesBancaires as $compteBancaire) {
                    if ($pret = Pret::where('id_compte', $compteBancaire->id)->get())
                        array_push($pretArray, $pret);
                }
                return PretResource::collection(collect($pretArray)->flatten());
            } else {
                return response()->json(['ERREUR' => "Veuillez entrer un id_user valide."], 400);
            }
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
        // cette méthode crée un compte bancaire qui va être le prêt rembourser au fil du temps
        // elle crée un prêt qui prend l'id du compte bancaire récemment crée et qui va rester généralement static
        // la requête prend en paramètre un id_demande, un taux_interet et une durée en mois
        // la demande à partir de laquelle le prêt va être créé passe à l'état d'approuvée et la date de traitement est entrée
        if ($request->routeIs('creationPretApi')) {
            $validation = Validator::make($request->all(), [
            'id_demande' => 'required',
            'taux_interet' => 'required|regex:/^\d+$/',
            'duree' => 'required|regex:/^\d+$/',
            ], [
            'id_demande.required' => 'Veuillez entrer le id de la demande de prêt approuvée.',
            'taux_interet.required' => 'Veuillez entrer le taux d\'interêt du prêt.',
            'taux_interet.regex' => 'Le taux d\'intérêt doit être numérique.',
            'duree.required' => 'Veuillez entrer la durée.',
            'duree.regex' => 'La durée doit être en mois.',
            ]);
            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDecode = $validation->validated();

            if (!Demande::find($contenuDecode['id_demande'])) {
                return response()->json(['ERREUR' => "Cette demande n'existe pas."], 400);
            } else {
                $demande = Demande::find($contenuDecode["id_demande"]);

                // si la demande a déjà été approuvée ou refusée l'ajout ne fonctionnera pas
                if($demande->id_etat_demande == 1 || $demande->id_etat_demande == 2) {
                    return response()->json(['NOTE' => "Cette demande a déjà traitée."], 400);
                }
            }

            try {
                $date_debut = date('Y-m-d');
                $date_echeance = date('Y-m-d', strtotime('+' . $contenuDecode['duree'] . ' month'));
                $taux = $contenuDecode['taux_interet']/100;

                $compteBancaire = CompteBancaire::create([
                    'nom' => $demande->raison,
                    'solde' => $demande->montant,
                    'taux_interet' => $taux,
                    'id_user' => $demande->id_demandeur,
                    'est_valide' => 1
                ]);

                Pret::create([
                    'nom' => $demande->raison,
                    'montant' => $demande->montant,
                    'date_debut' => $date_debut,
                    'date_echeance' => $date_echeance,
                    'est_valide' => 1,
                    'id_compte' => $compteBancaire->id
                ]);

                $demande->id_etat_demande = 1;
                $demande->date_traitement = now();
                $demande->save();

                return response()->json(['SUCCES' => 'Le prêt a été créé avec succès.'], 200);
            } catch (QueryException $erreur) {
                report($erreur);
                return response()->json(['ERREUR' => 'Le prêt n\'a pas été créé.'], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Pret $pret)
    {
        if ($request->routeIs('PretApi')) {
            $pret = Pret::find($request['id']);

            if (empty($pret) || Pret::where('est_valide', 0)->find($request['id']))
                return response()->json(['ERREUR' => 'Ce pret n\'existe pas ou a été désactivé.'], 400);

            return new PretResource($pret);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pret $pret)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pret $pret)
    {
        // le prêt est un objet pratiquement static le nom est le seul paramètre possible de modifié
        if ($request->routeIs('modificationPretApi')) {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'nom' => 'required',
                ], [
                'id.required' => 'Le prêt est introuvable.',
                'nom.required' => 'Veuillez entrer le nouveau nom du prêt.'
                ]);
                if ($validation->fails()) {
                    return back()->withErrors($validation->errors())->withInput();
                }

            $contenuDecode = $validation->validated();

            if (!Pret::find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Ce prêt n\'existe pas.'], 400);
            } elseif (Pret::where('est_valide', 0)
                ->find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Ce prêt a été désactivé.'], 400);
            }

            $pret = Pret::find($contenuDecode['id']);
            $pret->nom = $contenuDecode['nom'];

            if ($pret->save())
                return response()->json(['SUCCES' => 'La modification du prêt a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'La modification du prêt a échoué.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Pret $pret)
    {
        if ($request->routeIs('desactivationPretApi')) {
            if (!Pret::find($request['id'])) {
                return response()->json(['ERREUR' => 'Ce prêt n\'existe pas.'], 400);
            } elseif (Pret::where('est_valide', 0)
                ->find($request['id'])) {
                return response()->json(['ERREUR' => 'Ce prêt a été désactivé.'], 400);
            }

            $pret = Pret::find($request['id']);
            $pret->est_valide = 0;

            if ($pret->save())
                return response()->json(['SUCCES' => 'La désactivation du prêt a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'La désactivation du prêt a échoué.'], 400);
        }
    }
}
