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
            'id_envoyeur' => $this->id_envoyeur,
            'id_receveur' => $this->id_receveur,
            'id_conversation' => $this->id_conversation
        ];
    }
}
