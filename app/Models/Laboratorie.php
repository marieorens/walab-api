<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratorie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'description', 'address', 'image', 'pourcentage_commission'
    ];

    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class);
    }

    public function typeBilans(): HasMany
    {
        return $this->hasMany(TypeBilan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'laboratoire_id');
    }

    public function getWallet(): ?Wallet
    {
        return $this->user?->wallet;
    }
}
