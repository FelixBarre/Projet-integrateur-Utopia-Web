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
        return [
            'id' => $this->id,
            'montant' => $this->montant,
            'id_compte_envoyeur' => $this->comptes_bancaire->comptes->nom,
            'id_compte_receveur' => $this->comptes_bancaire_receveur->comptes->nom,
            'id_type_transaction'=>$this->type_transactions->label,
            'id_etat_transaction'=> $this->etat_transactions->label,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            ];
    }
}
