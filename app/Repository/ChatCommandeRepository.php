<?php

namespace App\Repository;

use App\Enum\StatutCommandeEnum;
use App\Events\GotMessage;
use App\Models\ChatCommande;
use App\Models\Commande;
use App\Models\User;
use App\Notifications\SendPushNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatCommandeRepository
{
    private $user;
    private $chatCommande;
    private $commande;

    public function __construct(User $user, ChatCommande $chatCommande, Commande $commande)
    {
        $this->user = $user;
        $this->chatCommande = $chatCommande;
        $this->commande = $commande;
    }

    public function getCommandeConversation(int $userId){
        $conversations = $this->commande->newQuery()
            ->select("code", DB::raw('MAX(created_at) as latest_created_at'))
            ->where(function($query) use ($userId) {
                $query->where('client_id', $userId)
                    ->orWhere('agent_id', $userId);
            })
            ->where('statut', '!=', StatutCommandeEnum::PENDING)
            ->groupBy("code")
            ->orderBy('latest_created_at', 'DESC')
            ->paginate(15);

        $conversations->getCollection()->transform(function ($query) {
            $cmd = Commande::where('code', $query->code)->first();
            if($cmd) {
                $query->statut = $cmd->statut;
                $query->created_at = $cmd->created_at;
            }
            return $query;
        });

        return $conversations;
    }

    public function create_ChatCommande($content, int $from, int $to, string $code_commande, string $type = 'text', ?string $attachment = null){

        // 1. Enregistrement BD
        $message = $this->chatCommande->newQuery()->create([
            "content" => $content,
            "from_id" => $from,
            "to_id" => $to,
            "code_commande" => $code_commande,
            "type" => $type,
            "attachment" => $attachment
        ]);

        // 2. Event Pusher (Mise à jour immédiate app)
        $tab = [
            'id' => $message->id,
            'code' => $message->code_commande,
            'content' => $message->content,
            'from_id' => $message->from_id,
            'to_id' => $message->to_id,
            'red_at' => $message->red_at,
            'created_at' => $message->created_at,
            'type' => $message->type,
            'attachment' => $message->attachment
        ];
        event(new GotMessage($tab));

        // 3. ENVOI DES NOTIFICATIONS PUSH
        try {
            $recipient = User::find($to);
            if ($recipient) {
                // On adapte le texte
                $text = ($type === 'audio') ? '🎤 Nouveau message vocal' : substr($content, 0, 50) . '...';

                // On envoie la notif avec le code de commande dans le titre
                $recipient->notify(new SendPushNotification(
                    'Message #' . $code_commande . ' 💬',
                    $text,
                    '/user/messagerie',
                    'Répondre'
                ));
            }
        } catch (\Exception $e) {
            Log::error("Erreur Push : " . $e->getMessage());
        }

        return $message;
    }

    public function getChatCommandeFor(string $code_commande): Builder
    {
        return $this->chatCommande->newQuery()
            ->where("code_commande", $code_commande)
            ->orderBy('created_at', 'DESC');
    }

    public function unreadCount(int $userId){
        return $this->chatCommande->newQuery()
            ->where('to_id', $userId)
            ->groupBy('from_id')
            ->selectRaw('from_id, Count(id) as count')
            ->whereRaw('red_at IS NULL')
            ->get()
            ->pluck('count', 'from_id');
    }
}
