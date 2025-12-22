<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Practitioner extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'secondary_specialties' => 'array',
        'documents_urls' => 'array',
        'languages_spoken' => 'array',
        'availability' => 'array',
        'validated_at' => 'datetime',
    ];

    /**
     * Attributes to append to JSON serialization
     */
    protected $appends = ['profile_completion', 'is_active'];

    /**
     * Get the user that owns the practitioner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the validator who validated the practitioner.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Calculate profile completion percentage.
     */
    public function getProfileCompletionAttribute(): int
    {
        $total = 20; // Base: order_number + profession

        if ($this->main_specialty) $total += 10;
        if ($this->bio && strlen($this->bio) >= 100) $total += 15;
        if ($this->languages_spoken) $total += 10;
        if ($this->years_experience) $total += 10;
        if ($this->affiliated_institution) $total += 5;
        if ($this->availability) $total += 10;
        if ($this->consultation_fee) $total += 5;
        if ($this->user && $this->user->url_profil && $this->user->url_profil !== 'profile/profile.png') $total += 10;
        if ($this->office_address) $total += 5;

        return min($total, 100);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->verification_status === 'approved' 
            && $this->profile_completion >= 70;
    }
}
