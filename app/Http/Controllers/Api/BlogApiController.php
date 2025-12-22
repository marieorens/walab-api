<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BlogImage;

class BlogApiController extends Controller
{
    // Récupérer tous les articles
    public function index()
    {
        try {
            // On récupère tout, trié du plus récent au plus vieux
            $blogs = Blog::latest()->get();

            // On boucle pour s'assurer que l'image a le lien complet (http://domaine.com/storage/...)
            // C'est vital pour que ton front-end affiche l'image sans prise de tête
            $blogs->transform(function ($blog) {
                if ($blog->image) {
                    $blog->image_url = asset($blog->image);
                } else {
                    $blog->image_url = null;
                }
                return $blog;
            });

            return response()->json([
                'status' => true,
                'message' => 'Liste des articles récupérée avec succès',
                'data' => $blogs
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération des articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Récupérer un seul article (détails)
    public function show($id)
    {
        try {
            $blog = Blog::find($id);

            if (!$blog) {
                return response()->json([
                    'status' => false,
                    'message' => 'Article introuvable',
                ], 404);
            }

            // Charger relation images et préparer les URLs
            $blog->load('images');
            $blog->image_url = $blog->image ? asset($blog->image) : null;
            // S'assurer que chaque image de la galerie a bien son url (si pas déjà)
            $blog->images->transform(function ($img) {
                if (empty($img->url) && !empty($img->path)) {
                    $img->url = asset('storage/' . $img->path);
                }
                return $img;
            });

            return response()->json([
                'status' => true,
                'message' => 'Article récupéré',
                'data' => $blog
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload a single image for use in WYSIWYG editor.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        try {
            $file = $request->file('image');
            $path = $file->store('public/blogs');
            $filename = basename($path);
            $url = asset('storage/blogs/' . $filename);

            return response()->json([
                'status' => true,
                'url' => $url,
                'filename' => $filename
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload and attach multiple images to a blog (gallery)
     */
    public function addImages(Request $request, $id)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $blog = Blog::findOrFail($id);
        $stored = [];

        foreach ($request->file('images', []) as $file) {
            $path = $file->store('public/blogs');
            $filename = basename($path);
            $url = asset('storage/blogs/' . $filename);

            $img = BlogImage::create([
                'blog_id' => $blog->id,
                'path' => 'blogs/' . $filename,
                'url' => $url,
            ]);

            $stored[] = $img;
        }

        return response()->json(['status' => true, 'images' => $stored], 201);
    }
}
