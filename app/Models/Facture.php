<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nom', 'description', 'montant_defini', 'jour_du_mois', 'id_fournisseur'];

}
