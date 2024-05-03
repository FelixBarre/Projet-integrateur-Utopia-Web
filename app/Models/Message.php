<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public function envoyeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_envoyeur');
    }

    public function receveur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_receveur');
    }
}
