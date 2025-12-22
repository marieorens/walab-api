<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="TypeBilan",
 *     title="Type de Bilan",
 *     description="Modèle représentant un bilan médical complet (pack d'examens)",
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="label", type="string", example="Bilan Général"),
 *     @OA\Property(property="price", type="number", format="float", example=15000),
 *     @OA\Property(property="description", type="string", example="Bilan complet incluant NFS, Glycémie..."),
 *     @OA\Property(property="icon", type="string", example="defaut_image.jpg"),
 *     @OA\Property(property="laboratorie_id", type="integer", example=1),
 *     @OA\Property(property="isactive", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
class TypeBilan extends Model
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
