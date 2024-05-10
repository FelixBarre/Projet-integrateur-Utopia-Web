<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CompteBancaire;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\CreditResource;
use Illuminate\Support\Facades\Validator;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->routeIs('CreditsApi')) {
            if(isset($request['id_user']) && User::find($request['id_user'])) {
                $creditArray = array();
                $comptesBancaires = CompteBancaire::where('id_user', $request['id_user'])->get();

                foreach ($comptesBancaires as $compteBancaire) {
                    if ($credit = Credit::where('id_compte', $compteBancaire->id)->where('est_valide', 1)->get())
                        array_push($creditArray, $credit);
                }
                return CreditResource::collection(collect($creditArray)->flatten());
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
        if ($request->routeIs('creationCreditApi')) {
            $validation = Validator::make($request->all(), [
            'id_user' => 'required|regex:/^\d+$/',
            'nom' => 'required',
            'limite' => 'required|regex:/^\d+(?:\.\d{2})?$/|max_digits:10',
            'taux_interet' => 'required|regex:/^\d+(?:\.\d{2})?$/|max_digits:3',
            ], [
            'id_user.required' => 'Veuillez entrer le id du user propriétaire du crédit.',
            'id_user.regex' => 'Le id du user doit être numérique.',
            'nom.required' => 'Veuillez entrer le nom du compte crédit.',
            'limite.required' => 'Veuillez entrer la limite du crédit.',
            'limite.regex' => 'La limite doit avoir un format avec deux chiffres après la virgule.',
            'limite.max_digits' => 'La limite ne doit pas dépasser 10 chiffres.',
            'taux_interet.required' => 'Veuillez inscrire un taux d\'intérêt avec deux chiffres après la virgule.',
            'taux_interet.regex' => 'Le taux d\'intérêt doit être numérique.',
            'taux_interet.max_digits' => 'Le taux d\'intérêt ne doit pas dépasser 3 chiffres.'
            ]);
            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDecode = $validation->validated();

            if (!User::find($contenuDecode['id_user'])) {
                return response()->json(['ERREUR' => 'Ce user n\'existe pas.'], 400);
            }

            try {
                $compteBancaire = CompteBancaire::create([
                    'nom' => $contenuDecode['nom'],
                    'solde' => 0.00,
                    'taux_interet' => $contenuDecode['taux_interet'],
                    'id_user' => $contenuDecode['id_user'],
                    'est_valide' => 1
                ]);

                Credit::create([
                    'nom' => $contenuDecode['nom'],
                    'limite' => $contenuDecode['limite'],
                    'est_valide' => 1,
                    'id_compte' => $compteBancaire->id
                ]);

                return response()->json(['SUCCES' => 'Le crédit a été créé avec succès.'], 200);
            } catch (QueryException $erreur) {
                report($erreur);
                return response()->json(['ERREUR' => 'Le crédit n\'a pas été créé.'], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Credit $credit)
    {
        if ($request->routeIs('CreditApi')) {
            $credit = Credit::find($request['id']);

            if (empty($credit) || Credit::where('est_valide', 0)->find($request['id']))
                return response()->json(['ERREUR' => 'Ce crédit n\'existe pas ou a été désactivé.'], 400);

            return new CreditResource($credit);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Credit $credit)
    {
        // le crédit est un objet pratiquement static le nom est le seul paramètre possible de modifié
        if ($request->routeIs('modificationCreditApi')) {
            $validation = Validator::make($request->all(), [
                'id' => 'required|regex:/^\d+$/',
                'nom' => 'required',
                ], [
                'id.required' => 'Le crédit est introuvable.',
                'id.regex' => 'Le id du crédit doit être numérique',
                'nom.required' => 'Veuillez entrer le nouveau nom du crédit.'
                ]);
                if ($validation->fails()) {
                    return response()->json(['ERREUR' => $validation->errors()], 400);
                }

            $contenuDecode = $validation->validated();

            if (!Credit::find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Ce crédit n\'existe pas.'], 400);
            } elseif (Credit::where('est_valide', 0)
                ->find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Ce crédit a été désactivé.'], 400);
            }

            $credit = Credit::find($contenuDecode['id']);
            $credit->nom = $contenuDecode['nom'];

            if ($credit->save())
                return response()->json(['SUCCES' => 'La modification du crédit a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'La modification du crédit a échoué.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Credit $credit)
    {
        if ($request->routeIs('desactivationCreditApi')) {
            if (!Credit::find($request['id'])) {
                return response()->json(['ERREUR' => 'Ce crédit n\'existe pas.'], 400);
            } elseif (Credit::where('est_valide', 0)
                ->find($request['id'])) {
                return response()->json(['ERREUR' => 'Ce crédit a été désactivé.'], 400);
            }

            $credit = Credit::find($request['id']);
            $credit->est_valide = 0;
            $compteCredit = CompteBancaire::find($credit->id_compte);
            $compteCredit->est_valide = 0;

            if ($credit->save() && $compteCredit->save())
                return response()->json(['SUCCES' => 'La désactivation du crédit a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'La désactivation du crédit a échoué.'], 400);
        }
    }
}
