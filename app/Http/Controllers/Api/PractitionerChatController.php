<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PractitionerMessage;
use App\Models\Practitioner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PractitionerContactNotification;
use Illuminate\Support\Facades\Notification;

class PractitionerChatController extends Controller
{
    /**
     * Récupérer toutes les conversations du praticien
     */
    public function getConversations(Request $request)
    {
        try {
            $user = $request->user();
            
            // Récupérer tous les messages où l'utilisateur est impliqué
            $conversations = PractitionerMessage::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->with(['sender:id,firstname,lastname,url_profil,role_id', 'receiver:id,firstname,lastname,url_profil,role_id', 'practitioner'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy(function ($message) use ($user) {
                    // Grouper par l'autre utilisateur (celui qui n'est pas l'utilisateur connecté)
                    return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
                })
                ->map(function ($messages, $otherUserId) use ($user) {
                    $latestMessage = $messages->first();
                    $otherUser = $latestMessage->sender_id === $user->id ? $latestMessage->receiver : $latestMessage->sender;
                    
                    // Compter les messages non lus
                    $unreadCount = $messages->where('receiver_id', $user->id)->where('is_read', false)->count();
                    
                    return [
                        'id' => $latestMessage->id,
                        'practitioner_id' => $latestMessage->practitioner_id,
                        'other_user' => [
                            'id' => $otherUser->id,
                            'firstname' => $otherUser->firstname,
                            'lastname' => $otherUser->lastname,
                            'email' => $otherUser->email ?? '',
                            'url_profil' => $otherUser->url_profil,
                            'role_id' => $otherUser->role_id,
                        ],
                        'last_message' => [
                            'content' => $latestMessage->content,
                            'type' => $latestMessage->type,
                            'created_at' => $latestMessage->created_at->toISOString(),
                            'is_mine' => $latestMessage->sender_id === $user->id,
                        ],
                        'unread_count' => $unreadCount,
                        'last_message_at' => $latestMessage->created_at->toISOString(),
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $conversations,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération conversations praticien: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des conversations.',
            ], 500);
        }
    }

    /**
     * Récupérer les messages d'une conversation spécifique
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'practitioner_id' => 'required|exists:practitioners,id',
            'other_user_id' => 'required|exists:users,id',
        ]);

        try {
            $user = $request->user();
            $practitionerId = $request->practitioner_id;
            $otherUserId = $request->other_user_id;

            // Récupérer les messages entre ces deux utilisateurs pour ce praticien
            $messages = PractitionerMessage::where('practitioner_id', $practitionerId)
                ->where(function ($query) use ($user, $otherUserId) {
                    $query->where(function ($q) use ($user, $otherUserId) {
                        $q->where('sender_id', $user->id)
                          ->where('receiver_id', $otherUserId);
                    })->orWhere(function ($q) use ($user, $otherUserId) {
                        $q->where('sender_id', $otherUserId)
                          ->where('receiver_id', $user->id);
                    });
                })
                ->with(['sender:id,firstname,lastname,url_profil', 'receiver:id,firstname,lastname,url_profil'])
                ->orderBy('created_at', 'asc')
                ->paginate(50);

            // Marquer les messages reçus comme lus
            PractitionerMessage::where('practitioner_id', $practitionerId)
                ->where('sender_id', $otherUserId)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'data' => $messages->items(),
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération messages praticien: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des messages.',
            ], 500);
        }
    }

    /**
     * Envoyer un message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'practitioner_id' => 'required|exists:practitioners,id',
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required_without:audio',
            'audio' => 'nullable|file|mimes:webm,mp3,wav|max:10240',
        ]);

        try {
            $user = $request->user();
            $practitionerId = $request->practitioner_id;
            $receiverId = $request->receiver_id;

            // Vérifier que le receiver_id n'est pas l'utilisateur lui-même
            if ($receiverId == $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas vous envoyer un message à vous-même.',
                ], 400);
            }

            $messageData = [
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'practitioner_id' => $practitionerId,
                'type' => 'text',
            ];

            // Gestion du message vocal
            if ($request->hasFile('audio')) {
                $audio = $request->file('audio');
                $filename = 'practitioner_vocal_' . time() . '_' . uniqid() . '.' . $audio->getClientOriginalExtension();
                $path = $audio->storeAs('practitioner_messages/audio', $filename, 'public');
                
                $messageData['type'] = 'audio';
                $messageData['attachment'] = $path;
                $messageData['content'] = 'Message vocal';
            } else {
                $messageData['content'] = $request->content;
            }

            $message = PractitionerMessage::create($messageData);
            $message->load(['sender:id,firstname,lastname,url_profil', 'receiver:id,firstname,lastname,url_profil']);

            // Envoyer notification aux admins si c'est le premier message d'un client vers un praticien
            try {
                $isFirstMessage = PractitionerMessage::where('sender_id', $user->id)
                    ->where('receiver_id', $receiverId)
                    ->where('practitioner_id', $practitionerId)
                    ->count() === 1; // On vient de créer le message, donc count === 1 signifie c'est le premier

                if ($isFirstMessage) {
                    $practitioner = Practitioner::with('user')->find($practitionerId);
                    
                    if ($practitioner && $practitioner->user) {
                        $practitionerName = $practitioner->user->firstname . ' ' . $practitioner->user->lastname;
                        $practitionerProfession = $this->getProfessionLabel($practitioner->profession);
                        $clientName = $user->firstname . ' ' . $user->lastname;

                        // Récupérer tous les admins (admin simple: 1, admin sup: 4)
                        $admins = User::whereIn('role_id', [1, 4])->get();

                        if ($admins->count() > 0) {
                            Log::info("Sending practitioner contact notification to " . $admins->count() . " admins");

                            Notification::send($admins, new PractitionerContactNotification(
                                'Contact Praticien',
                                'Nouveau contact client-praticien',
                                "L'utilisateur {$clientName} vient de contacter le {$practitionerProfession} {$practitionerName}",
                                "/practitioner/show/{$practitioner->id}"
                            ));

                            Log::info("Practitioner contact notification sent successfully");
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification de contact praticien: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès.',
                'data' => $message,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi message praticien: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du message.',
            ], 500);
        }
    }

    /**
     * Obtenir les informations d'une conversation (pour initialiser le chat)
     */
    public function getConversationInfo(Request $request, $practitionerId)
    {
        try {
            $user = $request->user();
            
            $practitioner = Practitioner::with('user:id,firstname,lastname,email,phone,url_profil,role_id')
                ->findOrFail($practitionerId);

            $otherUser = $practitioner->user;

            return response()->json([
                'success' => true,
                'data' => [
                    'practitioner_id' => $practitioner->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->firstname . ' ' . $otherUser->lastname,
                        'email' => $otherUser->email,
                        'phone' => $otherUser->phone,
                        'photo' => $otherUser->url_profil,
                        'role_id' => $otherUser->role_id,
                    ],
                    'practitioner' => [
                        'id' => $practitioner->id,
                        'profession' => $practitioner->profession,
                        'main_specialty' => $practitioner->main_specialty,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération info conversation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Praticien non trouvé.',
            ], 404);
        }
    }

    /**
     * Marquer tous les messages d'une conversation comme lus
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'practitioner_id' => 'required|exists:practitioners,id',
            'other_user_id' => 'required|exists:users,id',
        ]);

        try {
            $user = $request->user();
            
            PractitionerMessage::where('practitioner_id', $request->practitioner_id)
                ->where('sender_id', $request->other_user_id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Messages marqués comme lus.',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur marquage messages lus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage des messages.',
            ], 500);
        }
    }

    /**
     * Compter le nombre total de messages non lus
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $user = $request->user();
            
            $unreadCount = PractitionerMessage::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur comptage messages non lus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du comptage des messages.',
            ], 500);
        }
    }

    /**
     * Helper function to get profession label in French
     */
    private function getProfessionLabel($profession)
    {
        $professions = [
            'general_practitioner' => 'Médecin généraliste',
            'specialist_doctor' => 'Médecin spécialiste',
            'midwife' => 'Sage-femme',
            'nurse' => 'Infirmier(ère)',
            'nursing_assistant' => 'Aide-soignant(e)',
            'physiotherapist' => 'Kinésithérapeute',
            'psychologist' => 'Psychologue',
            'nutritionist' => 'Nutritionniste',
        ];

        return $professions[$profession] ?? $profession;
    }
}
