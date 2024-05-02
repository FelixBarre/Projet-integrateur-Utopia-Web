<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\EtatDemande;
use Illuminate\Http\Request;

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demande $demande)
    {
        //
    }
}
