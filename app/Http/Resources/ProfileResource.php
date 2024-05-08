<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'no_civique' => $this->no_civique,
            'no_porte' => $this->no_porte,
            'rue' => $this->rue,
            'id_ville' => $this->id_ville,
            'code_postal' => $this->code_postal,
            'password' => $this->password,
            'roles' => $this->roles
        ];
    }
}
