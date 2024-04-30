<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function employe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_employe');
    }
}
