<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PretResource extends JsonResource
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
            'nom' => $this->nom,
            'montant' => $this->montant,
            'date_debut' => $this->date_debut,
            'date_echeance' => $this->date_echeance,
            'est_valide' => $this->est_valide,
            'id_compte' => $this->id_compte
        ];
    }
}
