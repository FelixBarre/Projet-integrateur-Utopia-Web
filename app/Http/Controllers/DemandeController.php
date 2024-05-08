<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\EtatDemande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DemandePretResource;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->routeIs('demandesPret')) {
            return view('demandePret/demandesPret', [
            'demandes' => Demande::where('id_type_demande', 1)->get(),
            'etats' => EtatDemande::all()
            ]);
        } elseif ($request->routeIs('demandesPretFiltre')) {
            return view('demandePret/demandesPret', [
                'demandes' => Demande::where('id_type_demande', 1)->where('id_etat_demande', $request['filtre_demandePret'])->get(),
                'etats' => EtatDemande::all()
            ]);
        } elseif ($request->routeIs('demandesPretApi')) {
            return DemandePretResource::collection(Demande::where('id_demandeur', $request['id_user'])->get());
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
        if ($request->routeIs('creationDemandePretApi')) {
            $validation = Validator::make($request->all(), [
            'raison' => 'required',
            'montant' => 'required|regex:/^\d+(?:\.\d{2})?$/',
            'id_demandeur' => 'required|regex:/^\d+$/',
            ], [
            'raison.required' => 'Veuillez entrer la raison de la demande.',
            'montant.required' => 'Veuillez entrer la date de la demande.',
            'montant.regex' => 'Veuillez inscrire un montant avec deux chiffres après la virgule.',
            'id_demandeur.required' => 'L\auteur de la demande est introuvable.',
            'id_demandeur.regex' => 'Le id doit être numérique.',
            ]);
            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDecode = $validation->validated();

            if (!User::find($contenuDecode['id_demandeur'])) {
                return response()->json(['ERREUR' => 'Cet utilisateur n\'existe pas.'], 400);
            }

           try {
                Demande::create([
                    'date_demande' => now(),
                    'raison' => $contenuDecode['raison'],
                    'montant' => $contenuDecode['montant'],
                    'id_etat_demande' => 3,
                    'id_demandeur' => $contenuDecode['id_demandeur'],
                    'id_type_demande' => 1
                ]);

                return response()->json(['SUCCES' => 'La demande a été créé avec succès.'], 200);
            } catch (QueryException $erreur) {
                report($erreur);
                return response()->json(['ERREUR' => 'La demande n\'a pas été créé.'], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Demande $demande)
    {
        if ($request->routeIs('demandePret')) {
            return view('demandePret/demandePret', [
                'demande' => Demande::find($request['id_demande'])
            ]);
        } elseif ($request->routeIs('demandePretApi')) {
            $demande = Demande::find($request['id']);

            if (empty($demande))
            return response()->json(['ERREUR' => 'Cet demande n\'existe pas ou a été désactivé.'], 400);

            return new DemandePretResource($demande);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demande $demande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demande $demande)
    {
        if ($request->routeIs('modificationDemandePretApi')) {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'raison' => 'required',
                'montant' => 'required|regex:/^\d+(?:\.\d{2})?$/',
                'id_etat_demande' => 'required|regex:/^\d+$/'
                ], [
                'raison.required' => 'Veuillez entrer la raison de la demande.',
                'montant.required' => 'Veuillez entrer la date de la demande.',
                'montant.regex' => 'Veuillez inscrire un montant avec deux chiffres après la virgule.',
                'id_etat_demande.required' => 'Veuillez spécifié l\état de la demande.',
                'id_etat_demande.regex' => 'Le id doit être numérique.',
                ]);
                if ($validation->fails()) {
                    return back()->withErrors($validation->errors())->withInput();
                }

            $contenuDecode = $validation->validated();

            if (!Demande::find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Cette demande n\'existe pas.'], 400);
            } elseif (!EtatDemande::find($contenuDecode['id_etat_demande'])) {
                return response()->json(['ERREUR' => 'Le nouvel état de cette demande n\'existe pas.'], 400);
            }

            $demande = Demande::find($contenuDecode['id']);
            $demande->raison = $contenuDecode['raison'];
            $demande->montant = $contenuDecode['montant'];
            $demande->id_etat_demande = $contenuDecode['id_etat_demande'];

            // si la demande est refusée on lui assigne une date de traitement
            if ($contenuDecode['id_etat_demande'] == 2)
                $demande->date_traitement = now();


            if ($demande->save())
                return response()->json(['SUCCES' => 'La modification de la demande a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'La modification de la demande a échoué.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Demande $demande)
    {
        if ($request->routeIs('annulationDemandePretApi')) {
            if (!Demande::find($request['id'])) {
                return response()->json(['ERREUR' => 'Cette demande n\'existe pas.'], 400);
            }

            $demande = Demande::find($request['id']);
            $demande->id_etat_demande = 4;

            if ($demande->save())
                return response()->json(['SUCCES' => 'L\'annulation de la demande a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'L\'annulation de la demande a échoué.'], 400);
        }
    }
}
