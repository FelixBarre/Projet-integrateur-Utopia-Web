<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Http\Resources\FournisseurResource;
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
            return view('fournisseur/fournisseurs', [
            'fournisseurs'=>Fournisseur::orderBy('nom', 'asc')->get(),
            'employe'=>$employe,
            'date_time'=>$date_time]);
        }
        elseif($request->routeIs('fournisseursApi')){
            $fournisseurs = Fournisseur::all();

            if ($fournisseurs->isEmpty())
                return response()->json(['ERREUR' => 'Aucun fournisseur a été trouvé'], 400);

            return FournisseurResource::collection($fournisseurs);
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

        if($request->routeIs('fournisseurFilter')){

        $date_time = Carbon::now()->format('d-M-Y H:i');
        $employe =Auth::user();
        $fournisseur = Fournisseur::where('nom', $request['fournisseur'])->first();


            if(!$fournisseur){

                session()->flash('erreur', 'Aucun fournisseur n\'a été trouvé.');
                return redirect(route('fournisseurs'));
            }

            $idFournisseur = $fournisseur->id;
            $fournisseurs = Fournisseur::where('id', $idFournisseur)->get();

            return view('fournisseur.fournisseurs', [
                'fournisseurs'=>$fournisseurs,
                'employe'=>$employe,
                'date_time'=>$date_time]);

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
