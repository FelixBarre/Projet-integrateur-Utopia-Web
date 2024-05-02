<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Demande extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date_demande',
        'date_traitement',
        'raison',
        'montant',
        'id_etat_demande',
        'id_demandeur',
        'id_type_demande'
    ];

    public function etat_demande() : BelongsTo
    {
        return $this->belongsTo(EtatDemande::class, 'id_etat_demande');
    }

    public function user_demande() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_demandeur');
    }

    public function type_demande() : BelongsTo
    {
        return $this->belongsTo(TypeDemande::class, 'id_type_demande');
    }
}
