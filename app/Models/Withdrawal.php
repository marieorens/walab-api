<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'montant' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function markAsProcessing(int $adminId): void
    {
        $this->status = 'processing';
        $this->processed_by = $adminId;
        $this->save();
    }

    public function markAsCompleted(int $adminId, ?string $notes = null): void
    {
        $this->status = 'completed';
        $this->processed_at = now();
        $this->processed_by = $adminId;
        $this->notes = $notes;
        $this->save();

        $this->wallet->debit(
            $this->montant,
            "Retrait mensuel {$this->periode}",
            $adminId
        );

        $this->wallet->transactions()->create([
            'type' => 'withdrawal',
            'montant' => $this->montant,
            'montant_avant' => $this->wallet->balance + $this->montant,
            'montant_apres' => $this->wallet->balance,
            'description' => "Retrait mensuel {$this->periode}",
            'performed_by' => $adminId,
            'status' => 'completed',
        ]);
    }

    public function markAsFailed(int $adminId, ?string $notes = null): void
    {
        $this->status = 'failed';
        $this->processed_at = now();
        $this->processed_by = $adminId;
        $this->notes = $notes;
        $this->save();
    }

    public function cancel(int $adminId, ?string $notes = null): void
    {
        $this->status = 'cancelled';
        $this->processed_at = now();
        $this->processed_by = $adminId;
        $this->notes = $notes;
        $this->save();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForPeriod($query, string $periode)
    {
        return $query->where('periode', $periode);
    }

    public static function createForWallet(Wallet $wallet, string $periode, string $format = '%Y-%m'): ?self
    {
        $existingWithdrawal = self::where('wallet_id', $wallet->id)
            ->where('periode', $periode)
            ->first();

        if ($existingWithdrawal) {
            return null;
        }

        $montant = $wallet->getPeriodBalance($periode, $format);
        
        if ($montant <= 0) {
            return null;
        }

        return self::create([
            'wallet_id' => $wallet->id,
            'montant' => $montant,
            'periode' => $periode,
            'status' => 'pending',
        ]);
    }
}
