<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'id_compte_envoyeur' => $this->id_compte_envoyeur,
            'id_compte_receveur' => $this->id_compte_receveur,
            'id_type_transaction' => $this->id_type_transaction,
            'id_etat_transaction'=> $this->id_etat_transaction,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            ];
    }
}
