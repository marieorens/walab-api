<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'path',
        'url',
        'alt',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
