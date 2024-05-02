<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DemandePretResource extends JsonResource
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
            'date_demande' => $this->date_demande,
            'raison' => $this->raison,
            'montant' => $this->montant,
            'id_etat_demande' => $this->id_etat_demande,
            'id_demandeur' => $this->id_demandeur,
            'id_type_demande' => $this->id_type_demande
        ];
    }
}
