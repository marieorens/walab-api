<?php

namespace App\Repository;

use App\Enum\StatutCommandeEnum;
use App\Models\Commande;
use App\Models\Examen;
use App\Models\Paiement;
use App\Models\Resultat;
use App\Models\TypeBilan;
use App\Models\User;
use App\Models\Laboratorie;
use App\Notifications\CommandeNotification;
use App\Notifications\SendPushNotification;
use App\Mail\NewCommandeMail;
use App\Mail\NewCommandeAdminMail;
use App\Services\GenerateCodeService;
use App\Services\SendMailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommandeRepository
{
    private $commande;
    protected $generate_code_service;
    protected $paiement;
    protected $sendEmail;

    public function __construct(Commande $commande, GenerateCodeService $generate_code_service, PaiementRepository $paiement)
    {
        $this->commande = $commande;
        $this->paiement = $paiement;
        $this->generate_code_service = $generate_code_service;
        $this->sendEmail = new SendMailService();
    }

    public function create_Commande(Request $request, $user_id)
    {
        $code = $this->generate_code_service->generate();
        $montant = 0;
        $commandes = [];

        // Données communes
        $baseData = [
            'code' => $code,
            'type' => $request->type,
            'adress' => $request->adress,
            'description' => $request->description,
            'statut' => StatutCommandeEnum::PENDING,
            'date_prelevement' => isset($request->date_prelevement) ? $request->date_prelevement : null,
            'client_id' => $user_id,
            'payed' => $request->payed,
            'montant' => $request->montant,
        ];

        $fees_calculated = false; // To assign displacement fees only once

        if($request->examen_ids){
            foreach ($request->examen_ids as $examen_id) {
                $examen = Examen::find($examen_id);
                $service_price = $examen ? $examen->price : 0;
                
                $deplacement_fee = 0;
                if (!$fees_calculated) {
                    // Calculate total service sum to find displacement remainder
                    $total_service = 0;
                    if($request->examen_ids){
                        foreach($request->examen_ids as $eid) {
                            $ex = Examen::find($eid);
                            $total_service += $ex ? $ex->price : 0;
                        }
                    }
                    if($request->type_bilan_ids){
                        foreach($request->type_bilan_ids as $tid) {
                            $tb = TypeBilan::find($tid);
                            $total_service += $tb ? $tb->price : 0;
                        }
                    }
                    $deplacement_fee = max(0, floatval($request->montant) - $total_service);
                    $fees_calculated = true;
                }

                $data = array_merge($baseData, [
                    'examen_id' => $examen_id,
                    'frais_service' => $service_price,
                    'frais_deplacement' => $deplacement_fee
                ]);
                
                $commande = $this->commande->newQuery()->create($data);
                array_push($commandes, $commande);
            }
        }

        if($request->type_bilan_ids){
            foreach ($request->type_bilan_ids as $type_bilan_id) {
                $type_bilan = TypeBilan::find($type_bilan_id);
                $service_price = $type_bilan ? $type_bilan->price : 0;
                
                $deplacement_fee = 0;
                if (!$fees_calculated) {
                    // This block handles the case where there were no examen_ids
                    $total_service = 0;
                    if($request->type_bilan_ids){
                        foreach($request->type_bilan_ids as $tid) {
                            $tb = TypeBilan::find($tid);
                            $total_service += $tb ? $tb->price : 0;
                        }
                    }
                    $deplacement_fee = max(0, floatval($request->montant) - $total_service);
                    $fees_calculated = true;
                }

                $data = array_merge($baseData, [
                    'type_bilan_id' => $type_bilan_id,
                    'frais_service' => $service_price,
                    'frais_deplacement' => $deplacement_fee
                ]);
                
                $commande = $this->commande->newQuery()->create($data);
                array_push($commandes, $commande);
            }
        }

        if($request->payed || $request->mode == 'physique'){
            $status = ($request->mode == 'physique') 
                ? \App\Enum\StatutPaiementEnum::PHYSICAL->value 
                : ($request->status ?? \App\Enum\StatutPaiementEnum::PAYER->value);
                
            $this->paiement->create_paiement(
                $request->montant, 
                $code, 
                $request->transaction_id ?? 'physique_' . time(), 
                $request->reference ?? 'physique', 
                $request->mode ?? 'physique', 
                $status
            );
        }

        try {
            // Récupérer la première commande pour les notifications
            $firstCommande = $commandes[0] ?? null;
            
            // Notification au CLIENT
            User::find($user_id)->notify(new CommandeNotification('Commande', 'Votre commande : ' . $code . ' est en attente de traitement.'));

            // Email au CLIENT
            if($firstCommande && $firstCommande->client) {
                $this->sendEmail->sendMail($firstCommande->client->id, 'Votre commande : ' . $code . ' est en attente de traitement.');
            }

            // NOTIFICATIONS AU LABORATOIRE
            if ($firstCommande) {
                // Récupérer le laboratoire concerné
                $laboratoire = null;
                if ($firstCommande->examen) {
                    $laboratoire = $firstCommande->examen->laboratorie;
                } elseif ($firstCommande->type_bilan) {
                    $laboratoire = $firstCommande->type_bilan->laboratorie;
                }

                if ($laboratoire && $laboratoire->user) {
                    Log::info("Sending notifications to lab: " . $laboratoire->name . " - Email: " . $laboratoire->user->email);
                    
                    // Notification interne au laboratoire
                    $laboratoire->user->notify(new CommandeNotification(
                        'Nouvelle Commande',
                        'Une nouvelle commande #' . $code . ' a été passée par ' . $firstCommande->client->firstname . ' ' . $firstCommande->client->lastname
                    ));

                    // Notification push au laboratoire
                    $laboratoire->user->notify(new SendPushNotification(
                        '🔔 Nouvelle Commande',
                        'Commande #' . $code . ' - ' . $firstCommande->type,
                        '/laboratoire/commande',
                        'Voir la commande'
                    ));

                    // Email au laboratoire
                    try {
                        Log::info("Attempting to send email to: " . $laboratoire->user->email);
                        Mail::to($laboratoire->user->email)->send(new NewCommandeMail($firstCommande, $laboratoire));
                        Log::info("Email sent successfully to laboratory: " . $laboratoire->user->email);
                    } catch (\Exception $e) {
                        Log::error("Erreur envoi email laboratoire: " . $e->getMessage());
                        Log::error("Stack trace: " . $e->getTraceAsString());
                    }
                }
            }

            // NOTIFICATION AUX ADMINS
            $admins = User::whereHas('role', function($query) {
                $query->whereIn('label', ['admin', 'admin Sup']);
            })->get();

            foreach ($admins as $admin) {
                // Notification interne
                $admin->notify(new CommandeNotification(
                    'Nouvelle Commande',
                    'Commande #' . $code . ' passée par ' . ($firstCommande ? $firstCommande->client->firstname : 'un client')
                ));
                
                // Email à l'admin
                try {
                    if ($admin->email) {
                        Log::info("Sending email to admin: " . $admin->email);
                        Mail::to($admin->email)->send(new NewCommandeAdminMail($firstCommande, $laboratoire ?? null));
                        Log::info("Email sent successfully to admin: " . $admin->email);
                    }
                } catch (\Exception $e) {
                    Log::error("Erreur envoi email admin: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            Log::error("Erreur Notif Creation: " . $e->getMessage());
        }

        return $commandes;
    }

    public function update_Commande(Request $request, int $user_id)
    {
        $Commande = Commande::where('id', $request->id)->first();
        if($Commande) {
            $Commande->update([
                'type' => $request->type,
                'adress' => $request->adress,
                'statut' => StatutCommandeEnum::PENDING,
                'examen_id' => $request->examen_id,
                'type_bilan_id' => $request->type_bilan_id,
                'client_id' => $user_id,
                'date_prelevement' => isset($request->date_prelevement) ? $request->date_prelevement : null,
            ]);
            $Commande->save();
        }
        return $Commande;
    }

    public function get_Commande(int $user_id)
    {
        return $this->commande->newQuery()
            ->select("code", DB::raw('MAX(payed) as payed'), DB::raw('MAX(montant) as montant'), DB::raw('MAX(type) as type'), DB::raw('MAX(statut) as statut'), DB::raw('count(*) as total'))
            ->where('client_id', $user_id)
            ->groupBy("code")
            ->orderBy(DB::raw('(select max(created_at) from commandes c2 where c2.code = commandes.code)'), 'DESC')
            ->paginate(15);
    }

    // List Helpers
    public function get_CommandeAgent(int $user_id) { return $this->listHelper('agent_id', $user_id); }
    public function get_CommandePending(int $user_id) { return $this->listHelper('client_id', $user_id, StatutCommandeEnum::PENDING); }
    public function get_CommandePendingAgent(int $user_id) { return $this->listHelper('agent_id', $user_id, StatutCommandeEnum::PENDING); }
    public function get_CommandeProgress(int $user_id) { return $this->listHelper('client_id', $user_id, StatutCommandeEnum::IN_PROGRESS); }
    public function get_CommandeProgressAgent(int $user_id) { return $this->listHelper('agent_id', $user_id, StatutCommandeEnum::IN_PROGRESS); }
    public function get_CommandeFinish(int $user_id) { return $this->listHelper('client_id', $user_id, StatutCommandeEnum::FINISH); }
    public function get_CommandeFinishAgent(int $user_id) { return $this->listHelper('agent_id', $user_id, StatutCommandeEnum::FINISH); }

    private function listHelper($field, $userId, $statut = null) {
        $query = $this->commande->newQuery()
            ->select("code", DB::raw('MAX(payed) as payed'), DB::raw('MAX(montant) as montant'), DB::raw('MAX(type) as type'), DB::raw('MAX(statut) as statut'), DB::raw('count(*) as total'))
            ->where($field, $userId);
        if($statut) $query->where('statut', $statut);

        return $query->groupBy("code")
            ->orderBy(DB::raw('(select max(created_at) from commandes c2 where c2.code = commandes.code)'), 'DESC')
            ->paginate(15);
    }

    public function get_CommandeByCode(string $code)
    {
        $commandes = $this->commande->newQuery()
            ->select(
                "code",
                DB::raw('(SELECT MAX(c2.agent_id) FROM commandes c2 WHERE c2.code = commandes.code) as agent_id'),
                DB::raw('MAX(type) as type'),
                DB::raw('MAX(statut) as statut'),
                DB::raw('MAX(description) as description'),
                DB::raw('count(*) as total'),
                DB::raw('MAX(date_prelevement) as date_prelevement')
            )
            ->where('code', $code)
            ->groupBy("code")
            ->orderBy(DB::raw('(select max(created_at) from commandes c2 where c2.code = commandes.code)'), 'DESC')
            ->get();

        $commandes->transform(function ($query) {
            $comment_list = Commande::where('code', $query->code)->get();
            $comment_list->map(function ($com) {
                if($com->type_bilan_id){
                    $com->info = TypeBilan::select('label', 'price')->where('id', $com->type_bilan_id)->first();
                } else {
                    $com->info = Examen::select('label', 'price')->where('id', $com->examen_id)->first();
                }
                $com->resultat = Resultat::where('commande_id', $com->id)->first();
                if(!$com->resultat) {
                    $com->resultat = Resultat::where('code_commande', $com->code)->whereNull('commande_id')->first();
                }
            });
            $query->agent = User::where('id', $query->agent_id)->first();
            $clientId = Commande::where('code', $query->code)->first()->client_id;
            $query->client = User::where('id', $clientId)->first();

            $query->commandes = $comment_list;
            $query->paiement = Paiement::where('code_commande', $query->code)->first();
            $query->resultat = Resultat::where('code_commande', $query->code)->first();
            return $query;
        });

        return $commandes;
    }

    // MISE À JOUR : Notification au changement de statut
    public function change_statut(string $code, StatutCommandeEnum $statut)
    {
        // On récupère toutes les lignes de la commande (car une commande peut avoir plusieurs examens)
        $commandes = Commande::where('code', $code)->orWhere('id', $code)->get();

        if($commandes->isEmpty()) return null;

        // Mise à jour
        foreach($commandes as $cmd) {
            $cmd->update(['statut' => $statut]);
        }

        // Notification Push
        try {
            $firstCmd = $commandes->first();
            $client = User::find($firstCmd->client_id);

            if ($client) {
                $titre = "Mise à jour commande #$code 📦";
                $message = "Le statut de votre commande a changé.";

                if ($statut === StatutCommandeEnum::IN_PROGRESS) {
                    $message = "Votre prélèvement est en cours de traitement.";
                } elseif ($statut === StatutCommandeEnum::FINISH) {
                    $titre = "Commande Terminée ✅";
                    $message = "Vos analyses sont terminées. Merci de votre confiance !";
                }

                $client->notify(new SendPushNotification(
                    $titre,
                    $message,
                    '/user/details/commande/' . $code,
                    'Voir'
                ));
            }
        } catch (\Exception $e) {
            Log::error("Erreur Push Statut : " . $e->getMessage());
        }

        return $commandes->first();
    }

    public function getTotals()
    {
        return $this->commande->newQuery()
            ->where('isdelete', false)
            ->sum('montant');
    }

    public function getCurrentWeekOrders()
    {
        return $this->commande->newQuery()
            ->where('isdelete', false)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('montant');
    }

    public function getLastWeekOrders()
    {
        return $this->commande->newQuery()
            ->where('isdelete', false)
            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->sum('montant');
    }

    public function getOrderPerMonth()
    {
        return $this->commande->newQuery()
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total_orders'))
            ->where('isdelete', false)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
