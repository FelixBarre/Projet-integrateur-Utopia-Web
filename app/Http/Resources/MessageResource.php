<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'texte' => $this->texte,
            'chemin_du_fichier' => $this->chemin_du_fichier,
            'date_heure_supprime' => $this->date_heure_supprime,
            'envoyeur' => $this->envoyeur,
            'receveur' => $this->receveur,
            'id_conversation' => $this->id_conversation
        ];
    }
}
