<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\User;
use App\Models\TypeTransaction;
use App\Models\Transaction;
use App\Models\TypeDemande;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rapport.rapports', [
            'rapports' => Rapport::All()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rapport.nouveauRapport', [
            'type_transactions' => TypeTransaction::All(),
            'type_demandes' => TypeDemande::All()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'titre' => 'required|max:255',
            'description' => 'required|max:255',
            'date_debut' => 'required|date|before_or_equal:date_fin',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type_transactions' => 'array',
            'type_transactions.*' => 'integer',
            'type_demandes' => 'array',
            'type_demandes.*' => 'integer',
           ], [
            'titre.required' => 'Veuillez entrer un titre.',
            'titre.max' => 'Le titre doit avoir moins de 255 caractères.',
            'description.required' => 'Veuillez entrer une description.',
            'description.max' => 'La description doit avoir moins de 255 caractères.',
            'date_debut.required' => 'Veuillez entrer une date de début.',
            'date_debut.date' => 'Veuillez entrer une date de début valide.',
            'date_debut.before_or_equal' => 'Veuillez entrer une date de début avant la date de fin.',
            'date_fin.required' => 'Veuillez entrer une date de fin.',
            'date_fin.date' => 'Veuillez entrer une date de début valide.',
            'date_fin.after_or_equal' => 'Veuillez entrer une date de fin après la date de début.',
            'type_transactions.array' => 'Types de transactions reçues incorrectement!',
            'type_transactions.*.integer' => 'Les types de transactions doivent être identifiées avec leur id (nombre entier).',
            'type_demandes.array' => 'Types de demandes reçues incorrectement!',
            'type_demandes.*.integer' => 'Les types de demandes doivent être identifiées avec leur id (nombre entier).'
           ]);

        if ($validation->fails())
            return back()->withErrors($validation->errors())->withInput();

        $contenuFormulaire = $validation->validated();

        $chemin = $this->genererRapport($contenuFormulaire);

        $rapport = Rapport::create([
            'titre' => $contenuFormulaire['titre'],
            'description' => $contenuFormulaire['description'],
            'date_debut' => $contenuFormulaire['date_debut'],
            'date_fin' => $contenuFormulaire['date_fin'],
            'date_creation' => date('Y-m-d'),
            'chemin_du_fichier' => $chemin,
            'id_employe' => Auth::id()
        ]);

        return $this->index($request);
    }

    public function genererRapport($contenuFormulaire) {
        $chemin = '/rapports/' . $contenuFormulaire['titre'] . date('_Y-m-d_H-i-s_') . '.pdf';

        $transactions = array();
        $demandes = array();

        if (isset($contenuFormulaire['type_transactions'])) {
            $transactions = Transaction::whereIn('id_type_transaction', $contenuFormulaire['type_transactions'])
                                ->whereBetween('created_at', [$contenuFormulaire['date_debut'], $contenuFormulaire['date_fin']])
                                ->get();
        }

        if (isset($contenuFormulaire['type_demandes'])) {
            $demandes = Demande::whereIn('id_type_demande', $contenuFormulaire['type_demandes'])
                                    ->whereBetween('date_demande', [$contenuFormulaire['date_debut'], $contenuFormulaire['date_fin']])
                                    ->get();
        }

        $pdf = Pdf::loadView('rapport.rapport', [
            'titre' => $contenuFormulaire['titre'],
            'dates' => 'Du ' . $contenuFormulaire['date_debut'] . ' au ' . $contenuFormulaire['date_fin'],
            'transactions' => $transactions,
            'demandes' => $demandes
        ]);

        $content = $pdf->download()->getOriginalContent();

        Storage::put('public' . $chemin, $content);

        return 'storage' . $chemin;
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapport $rapport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rapport $rapport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rapport $rapport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rapport $rapport)
    {
        //
    }
}
