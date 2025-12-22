<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Practitioner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PractitionerApiController extends Controller
{
    /**
     * View any practitioner profile (for admin and users)
     */
    public function viewProfile($id)
    {
        $practitioner = Practitioner::with(['user', 'validator'])
            ->where('id', $id)
            ->first();

        if (!$practitioner) {
            return response()->json([
                'success' => false,
                'message' => 'Praticien non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $practitioner
        ]);
    }

    /**
     * Get authenticated practitioner profile
     */
    public function getProfile()
    {
        $user = Auth::user();
        
        $practitioner = Practitioner::where('user_id', $user->id)
            ->with(['user', 'validator'])
            ->first();

        if (!$practitioner) {
            return response()->json([
                'success' => false,
                'message' => 'Profil praticien non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $practitioner
        ]);
    }

    /**
     * Update practitioner profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $practitioner = Practitioner::where('user_id', $user->id)->first();

        if (!$practitioner) {
            return response()->json([
                'success' => false,
                'message' => 'Profil praticien non trouvé'
            ], 404);
        }

        $validated = $request->validate([
            'main_specialty' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'years_experience' => 'nullable|string',
            'affiliated_institution' => 'nullable|string|max:255',
            'office_address' => 'nullable|string|max:500',
            'consultation_fee' => 'nullable|numeric|min:0',
            'languages_spoken' => 'nullable|array',
            'languages_spoken.*' => 'string|max:100',
            'secondary_specialties' => 'nullable|array',
            'secondary_specialties.*' => 'string|max:255',
            'availability' => 'nullable|json',
            'accepts_new_patients' => 'nullable|boolean',
            'emergency_availability' => 'nullable|boolean',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
        ]);

        // Mettre à jour les informations du user (téléphone et ville)
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('city')) {
            $user->city = $request->city;
        }
        $user->save();

        // Gérer le certificat si fourni
        if ($request->hasFile('certificate')) {
            $certificate = $request->file('certificate');
            $path = $certificate->store('certificates', 'public');
            $validated['certificate_url'] = $path;
        }

        // Retirer phone et city de validated pour l'update du practitioner
        unset($validated['phone']);
        unset($validated['city']);

        $practitioner->update($validated);

        // Recalculer le profile_completion
        $practitioner->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => $practitioner,
            'profile_completion' => $practitioner->profile_completion
        ]);
    }

    /**
     * Get all approved practitioners (visible for patients)
     */
    public function getApprovedPractitioners(Request $request)
    {
        $query = Practitioner::with('user')
            ->where('verification_status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            });

        // Filtrer par profession si spécifié
        if ($request->has('profession')) {
            $query->where('profession', $request->profession);
        }

        // Filtrer par spécialité si spécifié
        if ($request->has('specialty')) {
            $query->where('main_specialty', 'like', '%' . $request->specialty . '%');
        }

        // Filtrer par ville si spécifié
        if ($request->has('city')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->city . '%');
            });
        }

        // Seulement les profils avec au moins 70% de complétion
        $practitioners = $query->get()->filter(function($practitioner) {
            return $practitioner->profile_completion >= 70;
        });

        return response()->json([
            'success' => true,
            'data' => $practitioners->values(),
            'total' => $practitioners->count()
        ]);
    }

    /**
     * Get practitioner statistics
     */
    public function getStatistics()
    {
        $user = Auth::user();
        
        $practitioner = Practitioner::where('user_id', $user->id)->first();

        if (!$practitioner) {
            return response()->json([
                'success' => false,
                'message' => 'Profil praticien non trouvé'
            ], 404);
        }

        $stats = [
            'profile_completion' => $practitioner->profile_completion,
            'is_visible' => $practitioner->profile_completion >= 70 && $practitioner->verification_status === 'approved',
            'verification_status' => $practitioner->verification_status,
            'member_since' => $practitioner->created_at->format('F Y'),
            'validated_at' => $practitioner->validated_at ? $practitioner->validated_at->format('d/m/Y') : null,
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get list of all practitioners grouped by profession
     * Only includes verified practitioners with completion >= 70%
     */
    public function listPractitioners()
    {
        // Récupérer tous les praticiens approuvés avec leurs relations
        $practitioners = Practitioner::with(['user'])
            ->where('verification_status', 'approved')
            ->get();

        // Filtrer par profile_completion >= 70% (attribut calculé dynamiquement)
        $filteredPractitioners = $practitioners->filter(function ($practitioner) {
            return $practitioner->profile_completion >= 70;
        })->values(); // Reset array keys

        return response()->json([
            'success' => true,
            'data' => $filteredPractitioners
        ]);
    }
}
