<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\CompteBancaire;
use Illuminate\Http\Request;
use App\Http\Resources\CreditResource;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($request->routeIs('CreditsApi')) {
            if(isset($request['id_user']) && User::find($request['id_user'])) {
                $creditArray = array();
                $comptesBancaires = CompteBancaire::where('id_user', $request['id_user'])->get();

                foreach ($comptesBancaires as $compteBancaire) {
                    if ($credit = Pret::where('id_compte', $compteBancaire->id)->get())
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Credit $credit)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credit $credit)
    {
        //
    }
}
