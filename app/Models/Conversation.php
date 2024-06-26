<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'ferme'
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'id_conversation', 'id')->whereNull('date_heure_supprime');
    }
}
