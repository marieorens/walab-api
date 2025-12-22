<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Commande extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'token_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id'); 
    }

    /**
     * Relation avec le résultat individuel (nouveau système)
     */
    public function resultat(): HasOne
    {
        return $this->hasOne(Resultat::class, 'commande_id', 'id');
    }

    public function resultat_global(): HasOne
    {
        return $this->hasOne(Resultat::class, 'code_commande', 'code');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id'); 
    }

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class);
    }

    public function type_bilan(): BelongsTo
    {
        return $this->belongsTo(TypeBilan::class);
    }    

    /**
     * Générer et stocker le QR Code pour la vérification agent/client
     * @return void
     */
    public function generateAndStoreQrCode(): void
    {
        try {
            // Générer un token unique de vérification
            $verificationToken = Str::random(64);
            
            // URL de vérification que le client scannera
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            $verificationUrl = $frontendUrl . '/verify-specialist/' . $verificationToken;

            // Générer le QR Code en PNG avec GD (pas Imagick) puis le convertir en base64
            // on utilise svg puis on le convertit en data URI, ou directement en base64
            $qrCodeSvg = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($verificationUrl);

            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

            // Mettre à jour la commande avec le QR Code
            $this->verification_token = $verificationToken;
            $this->token_expires_at = now()->addDays(7);
            $this->qr_code_base64 = $qrCodeBase64;
            $this->is_verified = false;
            $this->verified_at = null;
            $this->save();

            Log::info('QR Code généré pour la commande: ' . $this->id);

        } catch (\Exception $e) {
            Log::error('Erreur génération QR Code pour commande ' . $this->id . ': ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier si le token de vérification est valide
     * @return bool
     */
    public function isTokenValid(): bool
    {
        return $this->verification_token 
            && $this->token_expires_at 
            && $this->token_expires_at->isFuture();
    }

    /**
     * Vérifier si le QR Code doit être régénéré
     * @return bool
     */
    public function needsQrCodeRegeneration(): bool
    {
        if (!$this->qr_code_base64 || !$this->verification_token) {
            return true;
        }

        if (!$this->isTokenValid()) {
            return true;
        }

        return false;
    }

    /**
     * Marquer la commande comme vérifiée par le client
     * @return void
     */
    public function markAsVerified(): void
    {
        $this->is_verified = true;
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Obtenir l'URL de vérification du QR Code
     * @return string
     */
    public function getQrCodeUrl(): string
    {
        if (!$this->verification_token) {
            return '';
        }
        
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
        return $frontendUrl . '/verify-specialist/' . $this->verification_token;
    }

    /**
     * Régénérer le QR Code
     * @return void
     */
    public function regenerateQrCode(): void
    {
        $this->generateAndStoreQrCode();
    }
}