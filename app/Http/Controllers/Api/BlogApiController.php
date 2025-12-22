<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BlogImage;

/**
 * @OA\Tag(
 *     name="Blog",
 *     description="Gestion des articles de blog et des médias associés."
 * )
 */
class BlogApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog/list",
     *     summary="Liste des articles",
     *     tags={"Blog"},
     *     description="Récupère tous les articles de blog, triés du plus récent au plus ancien.",
     *     @OA\Response(
     *         response=200,
     *         description="Liste récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Liste des articles récupérée avec succès"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Blog"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */
    public function index()
    {
        try {
            $blogs = Blog::latest()->get();

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

    /**
     * @OA\Get(
     *     path="/api/blog/show/{id}",
     *     summary="Détail d'un article",
     *     tags={"Blog"},
     *     description="Récupère un article spécifique avec ses images de galerie.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'article",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article récupéré"),
     *             @OA\Property(property="data", ref="#/components/schemas/Blog")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article introuvable"
     *     )
     * )
     */
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

            $blog->load('images');
            $blog->image_url = $blog->image ? asset($blog->image) : null;

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
