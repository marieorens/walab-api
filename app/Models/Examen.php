<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examen extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function laboratorie(): BelongsTo
    {
        return $this->belongsTo(Laboratorie::class);
    }
    
}
