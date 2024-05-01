<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'date_creation',
        'chemin_du_fichier',
        'id_employe'
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_employe');
    }
}
