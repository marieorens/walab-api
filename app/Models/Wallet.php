<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_entrees' => 'decimal:2',
        'total_sorties' => 'decimal:2',
        'last_withdrawal_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class)->orderBy('created_at', 'desc');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class)->orderBy('created_at', 'desc');
    }

    public function credit(float $montant, ?string $description = null, ?int $paiementId = null, ?int $commandeId = null, ?int $performedBy = null): WalletTransaction
    {
        $montantAvant = $this->balance;
        $this->balance += $montant;
        $this->total_entrees += $montant;
        $this->save();

        return $this->transactions()->create([
            'paiement_id' => $paiementId,
            'commande_id' => $commandeId,
            'type' => 'credit',
            'montant' => $montant,
            'montant_avant' => $montantAvant,
            'montant_apres' => $this->balance,
            'description' => $description,
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    public function debit(float $montant, ?string $description = null, ?int $performedBy = null, bool $force = false): ?WalletTransaction
    {
        if (!$force && !$this->canDebit($montant)) {
            return null;
        }

        $montantAvant = $this->balance;
        $this->balance -= $montant;
        $this->total_sorties += $montant;
        $this->save();

        return $this->transactions()->create([
            'type' => 'debit',
            'montant' => $montant,
            'montant_avant' => $montantAvant,
            'montant_apres' => $this->balance,
            'description' => $description,
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    public function canDebit(float $montant): bool
    {
        return $this->balance >= $montant && $this->status === 'active';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function block(): void
    {
        $this->status = 'blocked';
        $this->save();
    }

    public function suspend(): void
    {
        $this->status = 'suspended';
        $this->save();
    }

    public function activate(): void
    {
        $this->status = 'active';
        $this->save();
    }

    public function getPeriodBalance(string $periode, string $format = '%Y-%m'): float
    {
        return $this->transactions()
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereRaw("DATE_FORMAT(created_at, ?) = ?", [$format, $periode])
            ->sum('montant');
    }

    public function getMonthlyBalance(string $periode = null): float
    {
        $periode = $periode ?? now()->format('Y-m');
        return $this->getPeriodBalance($periode, '%Y-%m');
    }

    public static function getOrCreateForUser(int $userId, string $type = 'laboratoire'): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'type' => $type],
            ['balance' => 0, 'pending_balance' => 0, 'total_entrees' => 0, 'total_sorties' => 0, 'status' => 'active']
        );
    }

    public static function getPlateforme(): ?self
    {
        return self::where('type', 'plateforme')->first();
    }
}
