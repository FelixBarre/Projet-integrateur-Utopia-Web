<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;


    public function comptes_bancaire() : BelongsTo
    {
        return $this->belongsTo(CompteBancaire::class, 'id_compte_envoyeur');
    }

    public function comptes_bancaire_receveur() : BelongsTo
    {
        return $this->belongsTo(CompteBancaire::class, 'id_compte_receveur');
    }

    public function type_transactions() : BelongsTo
    {
        return $this->belongsTo(TypeTransaction::class, 'id_type_transaction');
    }

    public function etat_transactions() : BelongsTo
    {
        return $this->belongsTo(EtatTransaction::class, 'id_etat_transaction');
    }



}
