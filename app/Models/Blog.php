<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="Blog",
 *     title="Article de Blog",
 *     description="Modèle d'un article",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Les bienfaits du bilan sanguin"),
 *     @OA\Property(property="slug", type="string", example="les-bienfaits-du-bilan-sanguin"),
 *     @OA\Property(property="meta_description", type="string", example="Découvrez pourquoi il est important..."),
 *     @OA\Property(property="content", type="string", example="<p>Contenu HTML...</p>"),
 *     @OA\Property(property="image_url", type="string", example="http://localhost:8000/storage/blogs/image.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image'
    ];

    public function images()
    {
        return $this->hasMany(BlogImage::class);
    }
}
