<?php

namespace App\Http\Controllers;

use App\Models\CompteBancaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CompteBancaireResource;

class CompteBancaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->routeIs('comptesBancairesApi'))
            return CompteBancaireResource::collection(CompteBancaire::where('est_valide', 1)->get());
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
        if ($request->routeIs('creationCompteBancaireApi')) {
            $validation = Validator::make($request->all(), [
            'nom' => 'required',
            'solde' => 'regex:/^\d+(?:\.\d{2})?$/',
            'taux_interet' => 'regex:/^\d+(?:\.\d{2})?$/',
            'id_user' => 'required'
            ], [
            'nom.required' => 'Veuillez entrer un nom pour votre compte.',
            'solde.regex' => 'Veuillez inscrire un solde avec deux chiffres après la virgule.',
            'taux_interet.regex' => 'Veuillez inscrire un taux d\'intérêt avec deux chiffres après la virgule.',
            'id_user.required' => 'Le propriétaire du compte est introuvable.',
            ]);
            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDecode = $validation->validated();

            if (!User::find($contenuDecode['id_user'])) {
                return response()->json(['ERREUR' => 'Cet utilisateur n\'existe pas.'], 400);
            }
           }

           try {
                if (isset($contenuDecode['solde']) && isset($contenuDecode['taux_interet'])) {
                    CompteBancaire::create([
                        'nom' => $contenuDecode['nom'],
                        'solde' => $contenuDecode['solde'],
                        'taux_interet' => $contenuDecode['taux_interet'],
                        'id_user' => $contenuDecode['id_user'],
                        'est_valide' => 1
                    ]);
                } elseif (!isset($contenuDecode['solde'])) {
                    CompteBancaire::create([
                        'nom' => $contenuDecode['nom'],
                        'solde' => 0.00,
                        'taux_interet' => $contenuDecode['taux_interet'],
                        'id_user' => $contenuDecode['id_user'],
                        'est_valide' => 1
                    ]);
                } elseif (!isset($contenuDecode['taux_interet'])) {
                    CompteBancaire::create([
                        'nom' => $contenuDecode['nom'],
                        'solde' => 0.00,
                        'taux_interet' => 0.01,
                        'id_user' => $contenuDecode['id_user'],
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
        if ($request->routeIs('modificationCompteBancaireApi')) {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'nom' => 'required',
                'solde' => 'regex:/^\d+(?:\.\d{2})?$/',
                'taux_interet' => 'regex:/^\d+(?:\.\d{2})?$/',
                ], [
                'id.required' => 'Le compte est introuvable.',
                'nom.required' => 'Veuillez mettre le nom du compte a modifié.',
                'solde.regex' => 'Veuillez inscrire un solde avec deux chiffres après la virgule.',
                'taux_interet.regex' => 'Veuillez inscrire un taux d\'intérêt avec deux chiffres après la virgule.',
                ]);
                if ($validation->fails()) {
                    return back()->withErrors($validation->errors())->withInput();
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
