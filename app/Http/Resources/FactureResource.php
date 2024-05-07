<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'nom'=>$this->nom,
            'description'=>$this->description,
            'montant_defini' => $this->montant_defini,
            'jour_du_mois' => $this->jour_du_mois,
            'id_fournisseur' => $this->id_fournisseur,
            ];
    }
}
