<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Resultat",
 *     title="Résultat d'analyse",
 *     description="Fichier PDF contenant les résultats médicaux",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="code_commande", type="string", example="CMD-8392"),
 *     @OA\Property(property="pdf_url", type="string", example="resultats/file.pdf"),
 *     @OA\Property(property="pdf_password", type="string", example="XYZ123", description="Mot de passe pour ouvrir le PDF"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
class Resultat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Relation avec la commande individuelle
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }
}
