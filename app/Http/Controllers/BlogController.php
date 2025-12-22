<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    // Afficher la liste
    public function index()
    {
        $user_auth = Auth::user();
        // On récupère les articles du plus récent au plus vieux
        $blogs = Blog::latest()->paginate(10);
        return view('user.blog.index', compact('blogs', 'user_auth'));
    }

    // Enregistrer un nouveau blog
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'content' => 'required',
                // Make cover image optional so admins can save articles without attaching a file.
                // Accept modern webp format too (Quill/editor uploads may generate webp)
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $input = $request->all();

            if ($request->hasFile('image')) {
                // On enregistre l'image dans le dossier 'public/blogs'
                $path = $request->file('image')->store('blogs', 'public');
                $input['image'] = 'storage/' . $path;
            }

            Blog::create($input);

            return redirect()->route('blog.index')->with('success', 'Article ajouté avec succès');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            // Validation failed: include errors in the session and log
            Log::warning('Blog store validation failed', [
                'errors' => $ve->errors(),
                'input' => $request->except(['image'])
            ]);
            return redirect()->back()->withErrors($ve->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Blog store exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['image'])
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de l\'article');
        }
    }

    // Mettre à jour un blog
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $blog = Blog::findOrFail($id);
        $input = $request->all();

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($blog->image) {
                $oldPath = str_replace('storage/', '', $blog->image);
                Storage::disk('public')->delete($oldPath);
            }

            // Mettre la nouvelle
            $path = $request->file('image')->store('blogs', 'public');
            $input['image'] = 'storage/' . $path;
        }

        $blog->update($input);

        return redirect()->route('blog.index')->with('success', 'Article modifié avec succès');
    }

    // Supprimer un blog
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        // Supprimer l'image du dossier
        if ($blog->image) {
            $oldPath = str_replace('storage/', '', $blog->image);
            Storage::disk('public')->delete($oldPath);
        }

        $blog->delete();

        return redirect()->route('blog.index')->with('success', 'Article supprimé');
    }
}
