<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatCommande;
use App\Models\PractitionerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UnifiedMessagesController extends Controller
{
    /**
     * Compter le nombre total de messages non lus (commandes + praticiens)
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $user = $request->user();

            // CORRECTION ICI : On utilise 'red_at' au lieu de 'is_read'
            // Un message est non lu si la date de lecture (red_at) est NULL
            $commandeUnreadCount = ChatCommande::where('to_id', $user->id)
                ->whereNull('red_at')
                ->count();

            // Pour les praticiens, je suppose que c'est aussi un timestamp 'read_at' ou 'is_read'.
            // Si ça plante aussi sur cette table, change 'is_read' par 'whereNull('read_at')'
            // Mais pour l'instant on garde is_read si tu n'as pas eu d'erreur dessus.
            $practitionerUnreadCount = PractitionerMessage::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            $totalUnreadCount = $commandeUnreadCount + $practitionerUnreadCount;

            return response()->json([
                'success' => true,
                'unread_count' => $totalUnreadCount,
                'breakdown' => [
                    'commandes' => $commandeUnreadCount,
                    'practitioners' => $practitionerUnreadCount,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur comptage messages non lus unifiés: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du comptage des messages.',
            ], 500);
        }
    }
}
