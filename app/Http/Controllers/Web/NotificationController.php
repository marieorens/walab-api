<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Récupérer toutes les notifications de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                \Log::warning('NotificationController: No authenticated user');
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Non authentifié'
                    ], 401);
                }
                return redirect()->route('login');
            }

            \Log::info('NotificationController: User authenticated', ['user_id' => $user->id, 'expects_json' => $request->expectsJson()]);

            // Si c'est une requête AJAX/API, retourner JSON
            if ($request->expectsJson() || $request->ajax()) {
                // Pagination: 20 notifications par page
                $perPage = $request->input('per_page', 20);
                $page = $request->input('page', 1);
                
                // Récupérer toutes les notifications
                $allNotifications = $user->notifications()
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($notification) {
                        return [
                            'id' => $notification->id,
                            'type' => $notification->data['type'] ?? 'Notification',
                            'title' => $notification->data['title'] ?? $notification->data['type'] ?? 'Notification',
                            'data' => $notification->data['data'] ?? $notification->data['body'] ?? 'Nouvelle notification',
                            'body' => $notification->data['body'] ?? $notification->data['data'] ?? '',
                            'url' => $notification->data['url'] ?? null,
                            'read_at' => $notification->read_at,
                            'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                            'created_at_human' => $notification->created_at->diffForHumans(),
                        ];
                    });

                // Pagination manuelle
                $total = $allNotifications->count();
                $notifications = $allNotifications->slice(($page - 1) * $perPage, $perPage)->values();

                // Compter les non lues
                $unreadCount = $user->unreadNotifications()->count();

                \Log::info('NotificationController: Returning notifications', [
                    'count' => $notifications->count(),
                    'total' => $total,
                    'page' => $page,
                    'per_page' => $perPage,
                    'unread_count' => $unreadCount
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $notifications,
                    'unread_count' => $unreadCount,
                    'pagination' => [
                        'total' => $total,
                        'per_page' => $perPage,
                        'current_page' => $page,
                        'last_page' => ceil($total / $perPage),
                        'from' => ($page - 1) * $perPage + 1,
                        'to' => min($page * $perPage, $total),
                    ],
                ]);
            }

            \Log::info('NotificationController: Returning view');
            // Sinon, retourner la vue Blade selon le rôle
            $roleId = $user->role_id;
            
            // Si c'est un laboratoire (role_id = 5)
            if ($roleId == 5) {
                return view('laboratoire.notification.notifications');
            }
            
            // Sinon vue admin pour les admins (role_id 1, 4) et agents (role_id 2)
            return view('admin.notifications');

        } catch (\Exception $e) {
            \Log::error('NotificationController error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des notifications',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Erreur lors de la récupération des notifications');
        }
    }

 
    public function markAsRead(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $notificationId = $request->input('id');
            
            if (!$notificationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID de notification manquant'
                ], 400);
            }

            $notification = $user->notifications()->find($notificationId);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification non trouvée'
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marquée comme lue',
                'unread_count' => $user->unreadNotifications()->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage de la notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Toutes les notifications ont été marquées comme lues',
                'unread_count' => 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage des notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function unreadCount(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du compteur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $notification = $user->notifications()->find($id);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification non trouvée'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification supprimée',
                'unread_count' => $user->unreadNotifications()->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
