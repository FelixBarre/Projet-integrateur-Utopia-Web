<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\EtatDemande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DemandePretResource;
use App\Http\Resources\DemandeDesactivationResource;

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
            if ($request['filtre_demandePret'] == "all") {
                return view('demandePret/demandesPret', [
                    'demandes' => Demande::where('id_type_demande', 1)->get(),
                    'etats' => EtatDemande::all()
                    ]);
            } else {
                return view('demandePret/demandesPret', [
                    'demandes' => Demande::where('id_type_demande', 1)->where('id_etat_demande', $request['filtre_demandePret'])->get(),
                    'etats' => EtatDemande::all()
                ]);
            }
        } elseif ($request->routeIs('demandesPretApi')) {
            //modif mobile
            $id_user = Auth::id();
            return DemandePretResource::collection(Demande::where('id_type_demande', 1)
            ->where('id_demandeur', $id_user)->get());
            //return DemandePretResource::collection(Demande::where('id_type_demande', 1)
            //->where('id_demandeur', $request['id_user'])->get());
        } elseif ($request->routeIs('demandesDesactivationApi')) {
            return DemandeDesactivationResource::collection(Demande::where('id_type_demande', 2)->get());
        } elseif ($request->routeIs('demandesDeDesactivation')) {
            return view('demandeDesactivation/demandesDesactivation', [
                'demandes' => Demande::where('id_type_demande', 2)->get(),
                'etats' => EtatDemande::all()
            ]);
        } elseif ($request->routeIs('demandesDeDesactivationFiltre')) {
            if ($request['filtre_demandeDesac'] == "all") {
                return view('demandeDesactivation/demandesDesactivation', [
                    'demandes' => Demande::where('id_type_demande', 2)->get(),
                    'etats' => EtatDemande::all()
                ]);
            } else {
                return view('demandeDesactivation/demandesDesactivation', [
                    'demandes' => Demande::where('id_type_demande', 2)->where('id_etat_demande', $request['filtre_demandeDesac'])->get(),
                    'etats' => EtatDemande::all()
                ]);
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
        if ($request->routeIs('creationDemandePretApi')) {
            $validation = Validator::make($request->all(), [
            'raison' => 'required',
            'montant' => 'required|regex:/^\d+(?:\.\d{2})?$/',
            'id_demandeur' => 'required|regex:/^\d+$/',
            ], [
            'raison.required' => 'Veuillez entrer la raison de la demande.',
            'montant.required' => 'Veuillez entrer le montant de la demande.',
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
        } elseif ($request->routeIs('creationDemandeDesactivationApi')) {
            $validation = Validator::make($request->all(), [
                'raison' => 'required',
                'id_demandeur' => 'required|regex:/^\d+$/',
                ], [
                'raison.required' => 'Veuillez entrer la raison de la demande.',
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
                        'montant' => null,
                        'id_etat_demande' => 3,
                        'id_demandeur' => $contenuDecode['id_demandeur'],
                        'id_type_demande' => 2
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
        } elseif ($request->routeIs('demandeDesactivationApi')) {
            $demande = Demande::where('id_type_demande', 2)->find($request['id']);

            if (empty($demande))
            return response()->json(['ERREUR' => 'Cet demande n\'existe pas ou a été désactivé.'], 400);

            return new DemandeDesactivationResource($demande);
        } elseif ($request->routeIs('demandeDeDesactivation')) {
            return view('demandeDesactivation/demandeDesactivation', [
                'demande' => Demande::find($request['id_demande'])
            ]);
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
                'id' => 'required|regex:/^\d+$/',
                'raison' => 'required',
                'montant' => 'required|regex:/^\d+(?:\.\d{2})?$/',
                'id_etat_demande' => 'required|regex:/^\d+$/'
                ], [
                'id.required' => 'La demande est introuvable.',
                'id.regex' => 'Le id doit être numérique.',
                'raison.required' => 'Veuillez entrer la raison de la demande.',
                'montant.required' => 'Veuillez entrer la date de la demande.',
                'montant.regex' => 'Veuillez inscrire un montant avec deux chiffres après la virgule.',
                'id_etat_demande.required' => 'Veuillez spécifié l\état de la demande.',
                'id_etat_demande.regex' => 'Le id doit être numérique.',
                ]);
                if ($validation->fails()) {
                    return response()->json(['ERREUR' => $validation->errors()], 400);
                }

            $contenuDecode = $validation->validated();

            if (!Demande::find($contenuDecode['id']) || !Demande::where('id_type_demande', 1)->find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Cette demande n\'existe pas.'], 400);
            } elseif (!EtatDemande::find($contenuDecode['id_etat_demande'])) {
                return response()->json(['ERREUR' => 'Le nouvel état de cette demande n\'existe pas.'], 400);
            }

            $demande = Demande::find($contenuDecode['id']);

            // si la demande a déjà été approuvée ou refusée la modifiaction ne fonctionnera pas
            if($demande->id_etat_demande == 1 || $demande->id_etat_demande == 2) {
                return response()->json(['NOTE' => "Cette demande a déjà traitée."], 400);
            }

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
        } elseif ($request->routeIs('modificationDemandeDesactivationApi')) {
            $validation = Validator::make($request->all(), [
                'id' => 'required|regex:/^\d+$/',
                'raison' => 'required',
                'id_etat_demande' => 'required|regex:/^\d+$/'
                ], [
                'id.required' => 'La demande est introuvable.',
                'id.regex' => 'Le id doit être numérique.',
                'raison.required' => 'Veuillez entrer la raison de la demande.',
                'id_etat_demande.required' => 'Veuillez spécifié l\'état de la demande.',
                'id_etat_demande.regex' => 'Le id_etat_demande doit être numérique.',
                ]);
                if ($validation->fails()) {
                    return response()->json(['ERREUR' => $validation->errors()], 400);
                }

            $contenuDecode = $validation->validated();

            if (!Demande::find($contenuDecode['id']) || !Demande::where('id_type_demande', 2)->find($contenuDecode['id'])) {
                return response()->json(['ERREUR' => 'Cette demande n\'existe pas.'], 400);
            } elseif (!EtatDemande::find($contenuDecode['id_etat_demande'])) {
                return response()->json(['ERREUR' => 'Le nouvel état de cette demande n\'existe pas.'], 400);
            }

            $demande = Demande::find($contenuDecode['id']);

            // si la demande a déjà été approuvée ou refusée la modifiaction ne fonctionnera pas
            if($demande->id_etat_demande == 1 || $demande->id_etat_demande == 2) {
                return response()->json(['NOTE' => "Cette demande a déjà été traitée."], 400);
            }

            $demande->raison = $contenuDecode['raison'];
            $demande->id_etat_demande = $contenuDecode['id_etat_demande'];

            // si la demande est approuvée ou refusée on lui assigne une date de traitement
            if ($contenuDecode['id_etat_demande'] == 1 || $contenuDecode['id_etat_demande'] == 2)
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
        // même code pour les deux types de demandes
        if ($request->routeIs('annulationDemandePretApi') || $request->routeIs('annulationDemandeDesactivationApi')) {
            if (!Demande::find($request['id'])) {
                return response()->json(['ERREUR' => 'Cette demande n\'existe pas.'], 400);
            }

            $demande = Demande::find($request['id']);

            if($demande->id_etat_demande == 1 || $demande->id_etat_demande == 2) {
                return response()->json(['NOTE' => "Cette demande a déjà été traitée."], 400);
            } elseif ($demande->id_etat_demande == 4) {
                return response()->json(['NOTE' => "Cette demande a déjà été annulée."], 400);
            }

            $demande->id_etat_demande = 4;

            if ($demande->save())
                return response()->json(['SUCCES' => 'L\'annulation de la demande a bien fonctionné.'], 200);
            else
                return response()->json(['ERREUR' => 'L\'annulation de la demande a échoué.'], 400);
        } elseif ($request->routeIs('destroyCompte')) {
            if ($request['actionDesac'] == "retour") {
                return view('demandeDesactivation/demandesDesactivation', [
                    'demandes' => Demande::where('id_type_demande', 2)->get(),
                    'etats' => EtatDemande::all()
                ]);
            } elseif ($request['actionDesac'] == "approuver") {
                $demande = Demande::find($request['id_demandeDesac']);
                if (!empty($demande) && $demande->id_etat_demande == 3) {
                    $user = User::find($demande->id_demandeur);
                    if (!empty($user) && $user->est_valide == 1) {
                        $demande->id_etat_demande = 1;
                        $user->est_valide = 0;
                        $user->save();
                        $demande->save();
                    } else {
                        session()->flash('erreur', 'Ce user n\'existe plus.');
                        return redirect('/demandesDeDesactivation');
                    }
                } else {
                    session()->flash('erreur', 'La demande n\'existe pas ou elle a déjà été traitée.');
                    return redirect('/demandesDeDesactivation');
                }

                return redirect('/demandesDeDesactivation');
            } elseif ($request['actionDesac'] == "refuser") {
                $demande = Demande::find($request['id_demandeDesac']);
                if ($demande->id_etat_demande == 3) {
                    $demande->id_etat_demande = 2;
                    $demande->save();

                    return redirect('/demandesDeDesactivation');
                } else {
                    session()->flash('erreur', 'La demande a déjà été traité.');
                    return redirect('/demandesDeDesactivation');
                }
            }
        }
    }
}
