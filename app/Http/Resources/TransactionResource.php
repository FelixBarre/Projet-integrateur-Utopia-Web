<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node\Stmt\Label;

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
            'id_compte_envoyeur' => $this->comptes_bancaire,
            'id_compte_receveur' => $this->comptes_bancaire_receveur,
            'id_type_transaction' => $this->id_type_transaction,
            'type_transactions'=>$this->type_transactions->label,
            'id_etat_transaction'=> $this->etat_transactions->label,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            ];
    }
}
