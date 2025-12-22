<?php

namespace App\Http\Controllers\Api\Commande;

use App\Enum\StatutCommandeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commande\CommandeRequest;
use App\Http\Requests\Commande\CommandeUpdateRequest;
use App\Http\Requests\Commande\TransactionRequest;
use App\Models\Commande;
use App\Models\Resultat;
use App\Models\User;
use App\Notifications\CommandeNotification;
use App\Notifications\SendPushNotification;
use App\Repository\ChatCommandeRepository;
use App\Repository\CommandeRepository;
use App\Services\SendMailService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CommandeController extends Controller
{
    /**
     * @var CommandeRepository
     */
    private $commandeRepository;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var ChatRepository
     */
    private $chatRepository;

    protected $sendEmail;

    public function __construct(CommandeRepository $commandeRepository, AuthManager $auth, ChatCommandeRepository $chatRepository)
    {
        $this->commandeRepository = $commandeRepository;
        $this->auth = $auth;
        $this->chatRepository = $chatRepository;
        $this->sendEmail = new SendMailService();
    }

    /**
     * listes Commande
     */
    public function listCommande(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Commandes',
            'data' => $this->commandeRepository->get_Commande($this->auth->user()->id)
        ]);
    }

    /**
     * listes Commande en attente
     */
    public function listCommandePending(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Commandes en attente',
            'data' => $this->commandeRepository->get_CommandePending($this->auth->user()->id)
        ]);
    }

    /**
     * listes Commande encours
     */
    public function listCommandeProgress(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Commandes encours',
            'data' => $this->commandeRepository->get_CommandeProgress($this->auth->user()->id)
        ]);
    }

    /**
     * listes Commande Agent en attente
     */
    public function listCommandePendingAgent(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Commandes en attente',
            'data' => $this->commandeRepository->get_CommandePendingAgent($this->auth->user()->id)
        ]);
    }

    /**
     * listes Commande Agent encours
     */
    public function listCommandeProgressAgent(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Commandes encours',
            'data' => $this->commandeRepository->get_CommandeProgressAgent($this->auth->user()->id)
        ]);
    }

    /**
     * listes Commande terminer
     */
    public function listCommandeFinish(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Commandes terminer',
            'data' => $this->commandeRepository->get_CommandeFinish($this->auth->user()->id)
        ]);
    }

    /**
     * listes Commande Agent terminer
     */
    public function listCommandeFinishAgent(Request $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes des Commandes terminer',
            'data' => $this->commandeRepository->get_CommandeFinishAgent($this->auth->user()->id)
        ]);
    }


    /**
     * get Commande
     */
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:commandes,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'get Commande',
            'data' => Commande::where('id', $request->id)->first()
        ]);
    }

    /**
     * get Commande par code
     */
    public function get_CommandeByCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'exists:commandes,code'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'get Commande',
            'data' => $this->commandeRepository->get_CommandeByCode($request->code)
        ]);
    }

    /**
     * create Commande
     */
    public function create(CommandeRequest $request)
    {
        // $request->client_id = $this->auth->user()->id;
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'create Commande',
            'data' => $this->commandeRepository->create_Commande($request, $this->auth->user()->id)
        ]);
    }

    /**
     * update Commande.
     */
    public function update(CommandeUpdateRequest $request)
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'update Commande',
            'data' => $this->commandeRepository->update_Commande($request, $this->auth->user()->id)
        ]);
    }


    /**
     * Delete Commande
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:Commandes,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $commande = Commande::where('id', $request->id)->first();
        $commande->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'delete Commande',
            // 'data' => $this->Commande
        ]);

    }

    /**
     * Change Statut
     */
    public function changeStatut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_commande' => ['required', 'string'],
            'statut' => [Rule::enum(StatutCommandeEnum::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'change statut Commande',
            'data' => $this->commandeRepository->change_statut($request->code_commande, $request->statut)
        ]);

    }

    /**
     * list commande Admin
     */
    public function listCommandeAdmin()
    {

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'list de tout les commandes',
            'data' => Commande::paginate(15)
        ]);
    }


    /**
     * listes des Commmande d'un Agent
     */
    public function listCommmandeAgent(Request $request)
    {
        if($request->agent_id){
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'list Agent du jour',
                'data' => $this->commandeRepository->get_CommandeAgent($request->agent_id)
            ]);
        }
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'listes Commendes Agent',
            'data' => $this->commandeRepository->get_CommandeAgent($this->auth->user()->id)
        ]);
    }


    /**
     *  lie agent commande
     */
    public function AssignAgentCommande(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_commande' => ['required', 'exists:commandes,code'],
            'agent_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $commandes = Commande::where('code', $request->code_commande)->get();
        $status = StatutCommandeEnum::IN_PROGRESS;
        foreach($commandes as $commande){
            $resultat = Resultat::where("code_commande", $commande->code)->first();
            if(!$resultat)
            {
                $commande->agent_id = $request->agent_id;
                $commande->statut = $status;
                $commande->save();
                // generation automatique du code qr
                $commande->generateAndStoreQrCode();
            }
        }
        $commande = Commande::where('code', $request->code_commande)->first();



        // Création du message automatique dans le chat
        $this->chatRepository->create_ChatCommande(
            'Bonjour, vous avez commender une liste d\'analyse',
            $request->agent_id,
            $commande->client_id,
            $request->code_commande
        );

        Log::info("=== DÉBUT DEBUG ASSIGNATION PUSH ===");
        Log::info("Commande Code: " . $commande->code);

        try {
            // 1. Check Agent
            $agent = User::find($request->agent_id);
            if ($agent) {
                $subCount = $agent->pushSubscriptions()->count();
                Log::info("Agent trouvé: ID " . $agent->id . " (" . $agent->email . ")");
                Log::info("L'agent a " . $subCount . " abonnement(s) Push.");

                if ($subCount > 0) {
                    $agent->notify(new SendPushNotification(
                        'Nouvelle Mission 🧬',
                        'Une nouvelle commande vous a été assignée.',
                        '/user/details/commande/' . $commande->code
                    ));
                    Log::info("-> Notification envoyée à l'Agent.");
                } else {
                    Log::error("-> ECHEC : L'agent n'a pas activé les notifications sur son navigateur !");
                }
            } else {
                Log::error("-> Agent introuvable en base de données.");
            }

            // 2. Check Client
            $client = User::find($commande->client_id);
            if ($client) {
                $subCountClient = $client->pushSubscriptions()->count();
                Log::info("Client trouvé: ID " . $client->id);
                Log::info("Le client a " . $subCountClient . " abonnement(s) Push.");

                if ($subCountClient > 0) {
                    $client->notify(new SendPushNotification(
                        'Infirmier en route 🚑',
                        'Votre commande est prise en charge.',
                        '/user/details/commande/' . $commande->code
                    ));
                    Log::info("-> Notification envoyée au Client.");
                } else {
                    Log::error("-> ECHEC : Le client n'a pas activé les notifications !");
                }
            }

        } catch (\Exception $e) {
            Log::error("CRASH PUSH : " . $e->getMessage());
        }
        Log::info("=== FIN DEBUG ===");

        // --- NOTIFICATIONS DATABASE (Existantes) ---
        User::find($commande->client_id)->notify(new CommandeNotification('commande', 'Votre commande : ' . $commande->code . ' est en cours de traitement. Veillez rejoindre la discussion!'));
        User::find($commande->agent_id)->notify(new CommandeNotification('commande', 'Une nouvelle commande vous a été assigner, Voici le code de la commande : ' . $commande->code. '. Veillez rejoindre la discussion!'));

        // --- NOTIFICATIONS PUSH (Nouvelles) ---


        // --- EMAILS (Existants) ---
        $this->sendEmail->sendMail($commande->agent_id, 'Une nouvelle commande vous a été assigner, Voici le code de la commande:' . $commande->code);
        $this->sendEmail->sendMail($commande->client_id, 'Vous êtes invité à rejoindre la nouvelle discussion de la commande ' . $request->code_commande . ' pour avoir plus de détail à propos de votre commande.');

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'lie agent commande',
            'data' => $commandes
        ]);
    }

    public function callback(Request $request)
    {
        $id = $request->query("id");
        $status = $request->query("status");
        $cle_fedaplay = config("app.cle_fedaplay");

        $response = Http::withHeaders([
            'Authorization' => "Bearer ". $cle_fedaplay,
            'Content-Type' => 'application/json',
        ])->get("https://api.fedapay.com/v1/transactions/".$id);

        if ($response->successful()) {
            $data = $response->json();
            $transactionData = $data['v1/transaction'];

            if ($transactionData['status'] = "approved") {

                $transactionRequest = new TransactionRequest();

                $transactionRequest->mapFromApiResponse($transactionData);
                $transactionRequest->merge(['payed' => true]);

                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "La commande a été créée avec succès via le callback.",
                    "data" => $this->commandeRepository->create_Commande($transactionRequest, $transactionRequest->client_id)
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Échec de la création de la commande via le callback. Veuillez vérifier les données et réessayer.",
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => 'Échec de la création de la commande via le callback.',
        ]);

    }
}
