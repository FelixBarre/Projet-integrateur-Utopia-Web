<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompteBancaireResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nom' => $this->nom,
            'solde' => $this->solde,
            'taux_interet' => $this->taux_interet,
            'est_valide' => $this->est_valide,
            'id_user' => $this->id_user
        ];
    }

}
