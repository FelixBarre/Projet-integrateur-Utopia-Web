<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $compteBancaireEnvoyeur = $this->comptes_bancaire;
        $compteBancaireReceveur = $this->comptes_bancaire_receveur;


        if ($compteBancaireEnvoyeur === null) {
            $compteBancaireEnvoyeur = $compteBancaireReceveur;
        }

        if ($compteBancaireReceveur === null) {
            $compteBancaireReceveur = $compteBancaireEnvoyeur;
        }

        return [
            'id' => $this->id,
            'montant' => $this->montant,
            'id_compte_envoyeur' => $compteBancaireEnvoyeur ? $compteBancaireEnvoyeur->comptes->nom : null,
            'id_compte_receveur' => $compteBancaireReceveur ? $compteBancaireReceveur->comptes->nom : null,
            'id_type_transaction'=>$this->type_transactions->label,
            'id_etat_transaction'=> $this->etat_transactions->label,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            ];
    }
}
