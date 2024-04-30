<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompteBancaire extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function transaction(): HasMany
    {
        return $this->HasMany(Transaction::class, 'id_user');
    }
}
