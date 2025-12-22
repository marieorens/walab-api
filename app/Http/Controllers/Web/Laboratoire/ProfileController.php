<?php

namespace App\Http\Controllers\Web\Laboratoire;

use App\Http\Controllers\Controller;
use App\Models\Laboratorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('laboratoire.login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $laboratory = $user->laboratorie;

        if (!$laboratory) {
            return redirect()->route('laboratoire.dashboard')
                ->with('error', 'Aucun laboratoire n\'est associé à votre compte. Veuillez contacter l\'administration.');
        }

        return view('laboratoire.profile.show', compact('laboratory'));
    }

    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('laboratoire.login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $laboratory = $user->laboratorie;

        if (!$laboratory) {
            return redirect()->route('laboratoire.dashboard')
                ->with('error', 'Aucun laboratoire n\'est associé à votre compte. Veuillez contacter l\'administration.');
        }

        return view('laboratoire.profile.edit', compact('laboratory'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $laboratory = Auth::user()->laboratorie;

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
        ];

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($laboratory->image && Storage::disk('public')->exists($laboratory->image)) {
                Storage::disk('public')->delete($laboratory->image);
            }

            $imageName = time() . '_' . $laboratory->id . '_lab.' . $request->file('image')->getClientOriginalExtension();
            $data['image'] = $request->file('image')->storeAs('laboratories', $imageName, 'public');
        }

        $laboratory->update($data);

        return redirect()->route('laboratoire.profile.show')->with('success', 'Profil mis à jour avec succès!');
    }
}
