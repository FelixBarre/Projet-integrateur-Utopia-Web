<?php

namespace App\Http\Controllers;

use App\Models\CompteBancaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompteBancaireResource;

class CompteBancaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Peut prendre en paramètre l'id_user
        // Retourne en Json tous les comptes d'un utilisateur si l'id_user est set sinon retourne tpous les comptes de
        // la base de données
        // impossible d'afficher les comptes s'il ont été désactivés
        if ($request->routeIs('comptesBancairesApi'))
            if(isset($request['id_user']))
                return CompteBancaireResource::collection(CompteBancaire::where('est_valide', 1)
                ->where('id_user', $request['id_user'])->get());
            else {
                //modif mobile
                $id_user = Auth::id();
                return CompteBancaireResource::collection(CompteBancaire::where('est_valide', 1)
                ->where('id_user', $id_user)->get());
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
        // Reçois une requête en Json avec en paramètre:
        // nom qui est obligatoire, solde qui falcultatif (par défaut: 0.00) avec deux chiffres après la virgule
        // taux_interet qui falcultatif (par défaut: 0.01) avec deux chiffres après la virgule et id_user qui est obligatoire
        if ($request->routeIs('creationCompteBancaireApi')) {
            $validation = Validator::make($request->all(), [
            'nom' => 'required',
            'solde' => 'regex:/^\d+(?:\.\d{2})?$/',
            'taux_interet' => 'regex:/^\d+(?:\.\d{2})?$/',
            //modif mobile
            //'id_user' => 'required'
            ], [
            'nom.required' => 'Veuillez entrer un nom pour votre compte.',
            'solde.regex' => 'Veuillez inscrire un solde avec deux chiffres après la virgule.',
            'taux_interet.regex' => 'Veuillez inscrire un taux d\'intérêt avec deux chiffres après la virgule.',
            //'id_user.required' => 'Le propriétaire du compte est introuvable.',
            ]);
            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDecode = $validation->validated();

            /*if (!User::find($contenuDecode['id_user'])) {
                return response()->json(['ERREUR' => 'Cet utilisateur n\'existe pas.'], 400);
            }*/
            $id_user = Auth::id();
           }

           try {
                if (isset($contenuDecode['solde']) && isset($contenuDecode['taux_interet'])) {
                    CompteBancaire::create([
                        'nom' => $contenuDecode['nom'],
                        'solde' => $contenuDecode['solde'],
                        'taux_interet' => $contenuDecode['taux_interet'],
                        'id_user' => $id_user,
                        'est_valide' => 1
                    ]);
                } elseif (!isset($contenuDecode['solde']) && !isset($contenuDecode['taux_interet'])) {
                    CompteBancaire::create([
                        'nom' => $contenuDecode['nom'],
                        'solde' => 0.00,
                        'taux_interet' => 0.01,
                        'id_user' => $id_user,
                        'est_valide' => 1
                    ]);
                } elseif (!isset($contenuDecode['solde'])) {
                    CompteBancaire::create([
                        'nom' => $contenuDecode['nom'],
                        'solde' => 0.00,
                        'taux_interet' => $contenuDecode['taux_interet'],
                        'id_user' => $id_user,
                        'est_valide' => 1
                    ]);
                } elseif (!isset($contenuDecode['taux_interet'])) {
                    CompteBancaire::create([
                        'nom' => $contenuDecode['nom'],
                        'solde' => $contenuDecode['solde'],
                        'taux_interet' => 0.01,
                        'id_user' => $id_user,
                        'est_valide' => 1
                    ]);
                }

                return response()->json(['SUCCES' => 'Le compte a été créé avec succès.'], 200);
           } catch (QueryException $erreur) {
                report($erreur);
                return response()->json(['ERREUR' => 'Le compte n\'a pas été créé.'], 500);
           }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, CompteBancaire $compteBancaire)
    {
        // Recois un id en paramètre et affiche en Json le compte correspondant à cet id
        // impossible d'afficher le compte s'il a été désactivé
        if ($request->routeIs('compteBancaireApi')) {
            $compteBancaire = CompteBancaire::find($request['id']);

            if (empty($compteBancaire) || CompteBancaire::where('est_valide', 0)->find($request['id']))
                return response()->json(['ERREUR' => 'Ce compte n\'existe pas ou a été désactivé.'], 400);

            return new CompteBancaireResource($compteBancaire);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompteBancaire $compteBancaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompteBancaire $compteBancaire)
    {
        // Reçois une requête en Json avec en paramètre:
        // le id du compte à modifié
        // nom qui est obligatoire (le compte va prendre ce nom), solde qui falcultatif avec deux chiffres après la virgule
        // taux_interet qui falcultatif avec deux chiffres après la virgule et id_user qui est obligatoire
        // impossible de modifier le compte s'il a été désactivé
        if ($request->routeIs('modificationCompteBancaireApi')) {
            $validation = Validator::make($request->all(), [
                'id' => 'required|regex:/^\d+$/',
                'nom' => 'required',
                'solde' => 'regex:/^\d+(?:\.\d{2})?$/',
                'taux_interet' => 'regex:/^\d+(?:\.\d{2})?$/',
                ], [
                'id.required' => 'Le compte est introuvable.',
                'id.regex' => 'Le id du compte doit être numérique.',
                'nom.required' => 'Veuillez mettre le nom du compte a modifié.',
                'solde.regex' => 'Veuillez inscrire un solde avec deux chiffres après la virgule.',
                'taux_interet.regex' => 'Veuillez inscrire un taux d\'intérêt avec deux chiffres après la virgule.',
                ]);
                if ($validation->fails()) {
                    return response()->json(['ERREUR' => $validation->errors()], 400);
                }

            $contenuDecode = $validation->validated();

            if (!CompteBancaire::find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Ce compte n\'existe pas.'], 400);
            } elseif (CompteBancaire::where('est_valide', 0)
                ->find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Ce compte a été désactivé.'], 400);
            }

            $compteBancaire = CompteBancaire::find($contenuDecode['id']);
            $compteBancaire->nom = $contenuDecode['nom'];
            if (isset($contenuDecode['solde']))
                $compteBancaire->solde = $contenuDecode['solde'];
            if (isset($contenuDecode['taux_interet']))
                $compteBancaire->taux_interet = $contenuDecode['taux_interet'];


            if ($compteBancaire->save())
                return response()->json(['SUCCES' => 'La modification du compte a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'La modification du compte a échoué.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CompteBancaire $compteBancaire)
    {
        // Recois un id en paramètre et met le compte correspondant à cet id à non-valide
        if ($request->routeIs('desactivationCompteBancaireApi')) {
            if (!CompteBancaire::find($request['id'])) {
                return response()->json(['ERREUR' => 'Ce compte n\'existe pas.'], 400);
            } elseif (CompteBancaire::where('est_valide', 0)
                ->find($request['id'])) {
                return response()->json(['ERREUR' => 'Ce compte a été désactivé.'], 400);
            }

            $compteBancaire = CompteBancaire::find($request['id']);
            $compteBancaire->est_valide = 0;

            if ($compteBancaire->save())
                return response()->json(['SUCCES' => 'La désactivation du compte a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'La désactivation du compte a échoué.'], 400);
        }
    }
}
