<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nom', 'description', 'montant_defini', 'jour_du_mois', 'id_fournisseur'];

    public function fournisseurs() : BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

}
