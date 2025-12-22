<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paiement extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'montant_laboratoire' => 'decimal:2',
        'montant_plateforme' => 'decimal:2',
        'pourcentage_applique' => 'decimal:2',
        'commission_processed' => 'boolean',
    ];

    public function laboratoire(): BelongsTo
    {
        return $this->belongsTo(Laboratorie::class, 'laboratoire_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function commandes()
    {
        return Commande::where('code', $this->code_commande)->get();
    }
}
