<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();

        if($request->routeIs('fournisseurs')){
            return view('fournisseur/fournisseurs', ['fournisseurs'=>Fournisseur::all(),
            'employe'=>$employe,
            'date_time'=>$date_time]);
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
    public function show(Request $request)
    {
        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();
        $fournisseurName = $request['nom'];
        $fournisseur = Fournisseur::where('nom', $fournisseurName)->get();


        if($request->routeIs('FournisseurFilter')){
            if(!$fournisseur)
                return back()->with('error', 'Fournisseur non trouvé!');

        return view('fournisseur/fournisseurs', [
            'fournisseurs'=>$fournisseur,
            'employe'=>$employe,
            'date_time'=>$date_time
        ]);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        //
    }
}
