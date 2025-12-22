<?php

namespace App\Http\Controllers\Api\Commande;

use App\Events\GotMessageTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commande\ChatCommandeRequest;
use App\Models\Commande;
use App\Repository\ChatCommandeRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Storage; // IMPORTANT

class chatCommandeController extends Controller
{
    protected $user;
    protected $chatCommandeRepository;

    public function __construct(ChatCommandeRepository $conversationsRepository, AuthManager $auth)
    {
        $this->chatCommandeRepository = $conversationsRepository;
        $this->user = $auth->user();
    }

    /**
     * list chat commande conversation.
     */
    public function listChatCommandeConversations()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'list chat commande conversations',
            'data' => $this->chatCommandeRepository->getCommandeConversation($this->user->id),
        ]);
    }

    /**
     * create send message (Text or Audio).
     */
    public function sendMessage(ChatCommandeRequest $request)
    {
        $commande = Commande::where('code', $request->code)->first();
        $from_id = null;
        $to_id = null;

        if($this->user->id == $commande->client_id){
            $from_id = $this->user->id;
            $to_id = $commande->agent_id;
        }elseif($this->user->id == $commande->agent_id){
            $from_id = $commande->agent_id;
            $to_id = $this->user->id;
        }

        if (!$from_id){
            return response()->json([
                'status' => 'echec',
                'message' => "Echec de l'envoie du message",
                'code' => 400,
            ]);
        }

        // Gestion de l'audio
        $type = 'text';
        $attachmentPath = null;
        $content = $request->content;

        if ($request->hasFile('audio')) {
            $type = 'audio';
            // Stocke dans storage/app/public/chat_audio
            $path = $request->file('audio')->store('chat_audio', 'public');
            $attachmentPath = '/storage/' . $path;

            // Si pas de texte fourni avec l'audio, on met une valeur par défaut
            if (empty($content)) {
                $content = 'Message vocal';
            }
        }

        $message = $this->chatCommandeRepository->create_ChatCommande(
            $content,
            $from_id,
            $to_id,
            $request->code,
            $type,           // Nouveau
            $attachmentPath  // Nouveau
        );

        return response()->json([
            'status' => 'success',
            'message' => 'send message',
            'code' => 200,
            'data' => $message,
        ]);
    }

    public function sendMessagetest()
    {
        $message = "test message pour le Got message event";
        event(new GotMessageTest($message, 'ccc'));

        return response()->json([
            'status' => 'success',
            'message' => 'send message',
            'code' => 200,
            'data' => $message,
        ]);
    }

    public function getChatCommandeFor(Request $request)
    {
        $request->validate([
            'code' => 'exists:App\Models\Commande,code'
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'get message for',
            'code' => 200,
            'data' => $this->chatCommandeRepository->getChatCommandeFor($request->code)->paginate(15),
        ]);
    }
}
