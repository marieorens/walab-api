<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="Examen",
 *     title="Examen Médical",
 *     description="Détail d'une analyse médicale disponible",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="label", type="string", example="NFS (Numération Formule Sanguine)"),
 *     @OA\Property(property="price", type="number", format="float", example=4500),
 *     @OA\Property(property="description", type="string", example="Analyse complète du sang..."),
 *     @OA\Property(property="icon", type="string", example="examen/nfs.png"),
 *     @OA\Property(property="laboratorie_id", type="integer", example=2),
 *     @OA\Property(property="isactive", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
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
