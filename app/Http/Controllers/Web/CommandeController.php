<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commande\CommandeRequest;
use App\Http\Requests\Commande\CommandeUpdateRequest;
use App\Enum\StatutCommandeEnum;
use App\Models\Commande;
use App\Models\Examen;
use App\Models\Paiement;
use App\Models\Resultat;
use App\Models\TypeBilan;
use App\Models\User;
use App\Notifications\CommandeNotification;
use App\Notifications\SendPushNotification;
use App\Repository\CommandeRepository;
use App\Repository\ResultatRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repository\ChatCommandeRepository;
use App\Services\SendMailService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResultatDisponibleMail;

class CommandeController extends Controller
{
    private $commandeRepository;
    private $auth;
    private $chatRepository;
    protected $sendEmail;

    public function __construct(CommandeRepository $commandeRepository, ChatCommandeRepository $chatRepository, AuthManager $auth)
    {
        $this->commandeRepository = $commandeRepository;
        $this->chatRepository = $chatRepository;
        $this->auth = $auth;
        $this->sendEmail = new SendMailService();
    }

    public function index()
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $commandes = Commande::select('code', DB::raw('MAX(id) as id'), DB::raw('MAX(created_at) as created_at'),
            DB::raw('(SELECT MAX(c2.agent_id) FROM commandes c2 WHERE c2.code = commandes.code) as agent_id'),
            DB::raw('(SELECT MAX(c2.client_id) FROM commandes c2 WHERE c2.code = commandes.code) as client_id'),
            DB::raw('MAX(type) as type'), DB::raw('MAX(statut) as statut'), DB::raw('count(*) as total'), DB::raw('MAX(date_prelevement) as date_prelevement'),
            DB::raw('MAX(adress) as adress'))
            ->where('isdelete', false)
            ->groupBy('code')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);

        $commandes->getCollection()->transform(function ($query) {
            $query->client = User::where('id', $query->client_id)->first();
            $query->agent = User::where('id', $query->agent_id)->first();
            $query->resultat = Resultat::where('code_commande', $query->code)->first();
            return $query;
        });

        $agents = User::where("role_id", 2)->Select("id", "firstname", "lastname")->get();
        return view('commande.index', compact('user_auth', 'commandes', 'agents'));
    }

    public function assigne(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $view = false;
        $commande = null;
        if($id){
            $commande = Commande::where("id", $id)->first();
        }

        $agents = User::where("role_id", 2)->Select("id", "firstname", "lastname")->get();
        return view('commande.assigne', compact('user_auth', 'view', 'commande', 'agents'));
    }

    public function assigne_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_commande' => ['required', 'exists:commandes,code'],
            'agent_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(["message" => $validator->errors()]);
        }

        $commandes = Commande::where('code', $request->code_commande)->get();
        $status = StatutCommandeEnum::IN_PROGRESS;
        $qrCodeGenerated = false;

        foreach($commandes as $commande){
            $resultat = Resultat::where("code_commande", $commande->code)->first();
            if(!$resultat)
            {
                $commande->agent_id = $request->agent_id;
                $commande->statut = $status;
                $commande->save();

                if (!$qrCodeGenerated) {
                    $commande->generateAndStoreQrCode();
                    $qrCodeGenerated = true;
                }
            }
        }
        $commande = Commande::where('code', $request->code_commande)->first();

        $this->chatRepository->create_ChatCommande(
            'Bonjour, vous avez commandé une liste d\'analyse',
            $request->agent_id,
            $commande->client_id,
            $request->code_commande
        );

        User::find($commande->client_id)->notify(new CommandeNotification('commande', 'Votre commande : ' . $commande->code . ' est en cours de traitement. Veillez rejoindre la discussion!'));
        User::find($commande->agent_id)->notify(new CommandeNotification('commande', 'Une nouvelle commande vous a été assigner, Voici le code de la commande : ' . $commande->code. '. Veillez rejoindre la discussion!'));

        try {
            $agent = User::find($request->agent_id);
            if($agent) {
                $agent->notify(new SendPushNotification(
                    'Nouvelle Mission ',
                    'Une nouvelle commande vous a été assignée.',
                    '/user/details/commande/' . $commande->code
                ));
            }
            $client = User::find($commande->client_id);
            if($client) {
                $client->notify(new SendPushNotification(
                    'Infirmier en route ',
                    'Un agent a pris en charge votre commande.',
                    '/user/details/commande/' . $commande->code
                ));
            }
        } catch (\Exception $e) {
            Log::error('Push Assign Error: ' . $e->getMessage());
        }

        try {
            if(config('mail.default') != 'log') {
                $this->sendEmail->sendMail($commande->agent_id, 'Une nouvelle commande vous a été assigner, Voici le code de la commande:' . $commande->code);
                $this->sendEmail->sendMail($commande->client_id, 'Vous êtes inviteé à rejoindre la nouvelle discussion de la commande ' . $request->code_commande . ' pour avoir plus de détail à propos de votre commande.');
            }
        } catch (\Exception $e) {}

        $message = "Commande assigné avec Success";
        return back()->with('success', $message);
    }

    public function create()
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $view = false;
        $examens = Examen::Select('id', 'label')->get();
        $bilans = TypeBilan::Select('id', 'label')->get();
        return view('commande.view', compact('user_auth', 'view', 'examens', 'bilans'));
    }

    public function create_user(User $client)
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $view = false;
        $examens = Examen::Select('id', 'label')->get();
        $bilans = TypeBilan::Select('id', 'label')->get();
        return view('commande.view', compact('user_auth', 'view', 'examens', 'bilans', 'client'));
    }

    public function store(CommandeRequest $request)
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $view = true;
        $commande = $this->commandeRepository->create_commande($request, $request->client_id);
        $message = "Commande creer avec Success";
        return view('commande.view', compact('user_auth', 'view', "commande", 'message'));
    }

    public function show(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $view = true;
        $commande = Commande::where("id", $id)->first();
        return view('commande.view', compact('user_auth', 'view', 'commande'));
    }

    public function edit(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $view = false;
        $commande = Commande::where("id", $id)->first();
        $examens = Examen::Select('id', 'label')->get();
        $bilans = TypeBilan::Select('id', 'label')->get();
        return view('commande.view', compact('user_auth', 'view', 'commande', 'examens', 'bilans'));
    }

    public function update(CommandeUpdateRequest $request, string $id)
    {
        $user_auth = User::where("id", $this->auth->user()?->getAuthIdentifier())->first();
        $view = true;
        $commande = $this->commandeRepository->update_commande($request, $request->client_id);
        $message = "Commande modifié avec Success";
        return view('commande.view', compact('user_auth', 'view'));
    }

    public function destroy(string $id)
    {
        Commande::where("id", $id)->first();
        return redirect()->route('home');
    }

    public function getData()
    {
        $ordersPerMonth =  $this->commandeRepository->getOrderPerMonth();
        $mois = [];
        $count_order = [];
        foreach ($ordersPerMonth as $order) {
            $date = Carbon::create(null, $order->month, 1);
            $monthName = $date->locale('fr')->monthName;
            array_push($mois, $monthName);
            array_push($count_order, $order->total_orders);
        }

        $data = [
            'labels' => $mois,
            'series' => [
                [
                    'name' => 'Commande',
                    'data' => $count_order
                ]
            ]
        ];

        return response()->json($data);
    }

    public function details($id)
    {
        $user_auth = auth()->user();

        $commande = Commande::with(['client', 'agent', 'examen', 'type_bilan'])
            ->where('id', $id)
            ->where('isdelete', false)
            ->firstOrFail();

        $sous_commandes = Commande::with(['examen.laboratorie', 'type_bilan.laboratorie', 'resultat'])
            ->where('code', $commande->code)
            ->where('isdelete', false)
            ->get();

        return view('commande.detailCommand', compact('commande', 'sous_commandes', 'user_auth'));
    }

    // --- UPLOAD RÉSULTATS (ADMIN) ---
    public function admin_upload_batch(Request $request)
    {
        Log::info("=== START DEBUG UPLOAD RESULTAT (ADMIN) ===");

        $request->validate([
            'code_commande' => 'required|string',
            'commande_ids' => 'required|string',
            'pdf_url' => 'required|mimes:pdf|max:10240',
        ]);

        $commande_ids = explode(',', $request->commande_ids);
        $code_commande = $request->code_commande;

        $commandes = Commande::with(['examen', 'type_bilan', 'client'])
            ->whereIn('id', $commande_ids)
            ->where('code', $code_commande)
            ->where('isdelete', false)
            ->get();

        if($commandes->count() !== count($commande_ids)) {
            Log::error("Erreur Upload: Commandes invalides ou manquantes.");
            return back()->with('error', 'Une ou plusieurs commandes sont invalides.');
        }

        if(!$request->hasFile('pdf_url')) {
            Log::error("Erreur Upload: Fichier PDF manquant.");
            return back()->with('error', 'Fichier PDF manquant.');
        }

        $pdf_name = time() . '_' . $code_commande . '_admin.pdf';
        $request->pdf_url->move(public_path() . "/resultat", $pdf_name);
        $pdf_path = "resultat/" . $pdf_name;

        $pdf_password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

        // Crypter le PDF avec le mot de passe
        $full_path = public_path($pdf_path);
        $repository = new ResultatRepository(new \App\Models\Resultat());
        try {
            $repository->protectPdf($full_path, $full_path, $pdf_password);
            Log::info('PDF admin crypté avec succès: ' . $pdf_path . ' avec mot de passe: ' . $pdf_password);
        } catch (\Exception $e) {
            Log::error('Erreur lors du cryptage PDF admin: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du cryptage du PDF.');
        }

        foreach($commandes as $cmd) {
            $resultat = Resultat::where('commande_id', $cmd->id)->first();

            if($resultat) {
                $resultat->pdf_url = $pdf_path;
                $resultat->pdf_password = $pdf_password;
                $resultat->save();
            } else {
                $resultat = new Resultat();
                $resultat->code_commande = $code_commande;
                $resultat->commande_id = $cmd->id;
                $resultat->pdf_url = $pdf_path;
                $resultat->pdf_password = $pdf_password;
                $resultat->save();
            }
        }

        // --- ENVOI EMAIL AUTOMATIQUE AU PATIENT AVEC LE CODE ---
        try {
            $client = $commandes->first()->client;
            if ($client && $client->email) {
                $clientName = $client->firstname . ' ' . $client->lastname;
                $commandeInfo = $commandes->first();
                Mail::to($client->email)->send(new ResultatDisponibleMail(
                    $commandeInfo,
                    $pdf_password,
                    $clientName
                ));
                Log::info('Email code PDF admin envoyé à: ' . $client->email . ' - Code: ' . $pdf_password);
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email code PDF admin: ' . $e->getMessage());
        }

        // --- PUSH NOTIFICATION ---
        try {
            $client = $commandes->first()->client;

            if($client) {
                Log::info("Client trouvé pour notif Résultat: ID " . $client->id . " - Subs: " . $client->pushSubscriptions()->count());

                $client->notify(new SendPushNotification(
                    'Résultats Disponibles ',
                    'Votre résultat est prêt ! Code de déchiffrement: ' . $pdf_password,
                    '/user/details/commande/' . $code_commande,
                    'Voir le résultat'
                ));
                Log::info("-> Notif envoyée (Upload Admin).");
            } else {
                Log::error("-> Client introuvable pour la commande.");
            }
        } catch (\Exception $e) {
            Log::error('Push Resultat Admin Error: ' . $e->getMessage());
        }

        $analyses_count = $commandes->count();
        $message = "Résultats uploadés et cryptés avec succès pour {$analyses_count} analyse(s) ! Code envoyé par email au patient. Code: {$pdf_password}";

        Log::info("=== END DEBUG UPLOAD ===");
        return back()->with('success', $message);
    }

    public function admin_terminer(Request $request, $code)
    {
        $commissionService = app(\App\Services\CommissionService::class);
        Log::info("=== START DEBUG TERMINER COMMANDE (ADMIN) ===");

        $sous_commandes = Commande::where('code', $code)
            ->where('isdelete', false)
            ->get();

        if($sous_commandes->isEmpty()) {
            Log::error("Erreur Terminer: Commande introuvable.");
            return back()->with('error', 'Commande introuvable.');
        }

        foreach($sous_commandes as $sc) {
            Log::info('[ACCR] Sous-commande', [
                'id' => $sc->id,
                'code' => $sc->code,
                'statut_avant' => $sc->statut,
                'montant' => $sc->montant
            ]);
            if($sc->statut != 'Terminer') {
                $sc->statut = 'Terminer';
                $sc->save();
                Log::info('[ACCR] Statut passé à Terminer', ['id' => $sc->id]);
                $laboratorie = null;
                if ($sc->examen && $sc->examen->laboratorie) {
                    $laboratorie = $sc->examen->laboratorie;
                } elseif ($sc->type_bilan && $sc->type_bilan->laboratorie) {
                    $laboratorie = $sc->type_bilan->laboratorie;
                }
                Log::info('[ACCR] Laboratorie trouvé', ['laboratorie' => $laboratorie]);
                if ($laboratorie) {
                    $sc->commissionService = $commissionService;
                    $commissionService->creditForCommande($sc);
                } else {
                    Log::warning('[ACCR] Aucun laboratoire trouvé pour la sous-commande', ['id' => $sc->id]);
                }
            }
        }

        try {
            $client = User::find($sous_commandes->first()->client_id);
            if($client) {
                Log::info("Client trouvé pour notif Terminer: ID " . $client->id . " - Subs: " . $client->pushSubscriptions()->count());

                $client->notify(new SendPushNotification(
                    'Commande Terminée 🎉',
                    'Vos analyses sont terminées. Merci de votre confiance !',
                    '/user/details/commande/' . $code
                ));
                Log::info("-> Notif envoyée (Terminer).");
            } else {
                Log::error("-> Client introuvable.");
            }
        } catch (\Exception $e) {
            Log::error('Push Terminer Admin Error: ' . $e->getMessage());
        }

        Log::info("=== END DEBUG TERMINER ===");
        return back()->with('success', 'Commande marquée comme Terminée.');
    }

    public function admin_delete_resultat($id)
    {
        $commande = Commande::with('resultat')->findOrFail($id);

        if(!$commande->resultat) {
            return back()->with('error', 'Aucun résultat à supprimer.');
        }

        if($commande->resultat->pdf_url && $commande->resultat->pdf_url != 'resultat_default.pdf') {
            $pdf_path = public_path($commande->resultat->pdf_url);
            if(file_exists($pdf_path)) {
                unlink($pdf_path);
            }
        }

        $commande->resultat->delete();

        return back()->with('success', 'Résultat supprimé avec succès.');
    }
}
