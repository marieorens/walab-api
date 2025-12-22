<?php

namespace App\Http\Controllers\Web\Laboratoire;

use App\Enum\StatutCommandeEnum;
use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Examen;
use App\Models\Laboratorie;
use App\Models\Resultat;
use App\Models\TypeBilan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log; // Import Log
use Illuminate\Support\Facades\Mail; 
use App\Notifications\ResultatNotification;
use App\Notifications\SendPushNotification; 
use App\Repository\ResultatRepository;
use DevRaeph\PDFPasswordProtect\Facades\PDFPasswordProtect;

class DashboardController extends Controller
{

    public function index()
    {
        $user_auth = User::where('id', Auth::user()->id)->first();

        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        if(!$laboratoire) {
            return redirect()->route('laboratoire.login')
                ->with('error', 'Aucun laboratoire n\'est associé à votre compte.');
        }

        $stats = [
            'total_examens' => Examen::where('laboratorie_id', $laboratoire->id)
                ->where('isdelete', false)
                ->count(),
            'total_bilans' => TypeBilan::where('laboratorie_id', $laboratoire->id)
                ->where('isdelete', false)
                ->count(),
            'total_resultats' => Resultat::where('isdelete', false)->count(),
            'commandes_en_attente' => Commande::where('statut', 'pending')
                ->where('isdelete', false)
                ->count()
        ];

        $recent_commandes = Commande::where('isdelete', false)
            ->where(function($query) use ($laboratoire) {
                $query->whereHas('examen', function($q) use ($laboratoire) {
                    $q->where('laboratorie_id', $laboratoire->id);
                })->orWhereHas('type_bilan', function($q) use ($laboratoire) {
                    $q->where('laboratorie_id', $laboratoire->id);
                });
            })
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('laboratoire.dashboard.index', compact('user_auth', 'laboratoire', 'stats', 'recent_commandes'));
    }

    // Liste des examens
    public function examens()
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        if(!$laboratoire) {
            return redirect()->route('laboratoire.login')->with('error', 'Aucun laboratoire associé.');
        }

        $examens = Examen::where('laboratorie_id', $laboratoire->id)
            ->where('isdelete', false)
            ->paginate(10);

        return view('laboratoire.examens.index', compact('user_auth', 'laboratoire', 'examens'));
    }

    // Ajouter examen
    public function store_examen(Request $request)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        $request->validate([
            'label' => 'required',
            'price' => 'required|numeric',
            'icon' => 'nullable|image',
        ]);

        $path = "defaut_image.jpg";
        if($request->hasFile('icon')){
            $image_url = time() . $request->icon->getClientOriginalName();
            $request->icon->move(public_path() . "/examen", $image_url);
            $path = "examen/" . $image_url;
        }

        $examen = new Examen();
        $examen->label = $request->label;
        $examen->price = $request->price;
        $examen->description = $request->description;
        $examen->icon = $path;
        $examen->laboratorie_id = $laboratoire->id;
        $examen->isactive = true;
        $examen->save();

        return back()->with('success', 'Examen ajouté avec succès.');
    }

    // Modifier examen
    public function update_examen(Request $request, $id)
    {
        $examen = Examen::find($id);

        if($request->hasFile('icon')){
            $image_url = time() . $request->icon->getClientOriginalName();
            $request->icon->move(public_path() . "/examen", $image_url);
            $path = "examen/" . $image_url;
        }else{
            $path = $examen->icon;
        }

        $examen->label = $request->label ?? $examen->label;
        $examen->price = $request->price ?? $examen->price;
        $examen->description = $request->description ?? $examen->description;
        $examen->icon = $path;
        $examen->save();

        return back()->with('success', 'Examen modifié avec succès.');
    }

    // Supprimer examen
    public function delete_examen($id)
    {
        $examen = Examen::find($id);
        $examen->isdelete = true;
        $examen->save();
        return back()->with('success', 'Examen supprimé avec succès.');
    }

    // Activer/Desactiver examen
    public function toggle_examen($id, $action)
    {
        $examen = Examen::find($id);
        $examen->isactive = $action == 'activer' ? true : false;
        $examen->save();
        return back()->with('success', 'Statut modifié avec succès.');
    }

    // Liste des bilans
    public function bilans()
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        if(!$laboratoire) {
            return redirect()->route('laboratoire.login')->with('error', 'Aucun laboratoire associé.');
        }

        $bilans = TypeBilan::where('laboratorie_id', $laboratoire->id)
            ->where('isdelete', false)
            ->paginate(10);

        return view('laboratoire.bilans.index', compact('user_auth', 'laboratoire', 'bilans'));
    }

    // Ajouter bilan
    public function store_bilan(Request $request)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        $request->validate([
            'label' => 'required',
            'price' => 'required|numeric',
            'icon' => 'nullable|image',
        ]);

        $path = "defaut_image.jpg";
        if($request->hasFile('icon')){
            $image_url = time() . $request->icon->getClientOriginalName();
            $request->icon->move(public_path() . "/typeBilan", $image_url);
            $path = "typeBilan/" . $image_url;
        }

        $bilan = new TypeBilan();
        $bilan->label = $request->label;
        $bilan->price = $request->price;
        $bilan->description = $request->description;
        $bilan->icon = $path;
        $bilan->laboratorie_id = $laboratoire->id;
        $bilan->isactive = true;
        $bilan->save();

        return back()->with('success', 'Bilan ajouté avec succès.');
    }

    // Modifier bilan
    public function update_bilan(Request $request, $id)
    {
        $bilan = TypeBilan::find($id);

        if($request->hasFile('icon')){
            $image_url = time() . $request->icon->getClientOriginalName();
            $request->icon->move(public_path() . "/typeBilan", $image_url);
            $path = "typeBilan/" . $image_url;
        }else{
            $path = $bilan->icon;
        }

        $bilan->label = $request->label ?? $bilan->label;
        $bilan->price = $request->price ?? $bilan->price;
        $bilan->description = $request->description ?? $bilan->description;
        $bilan->icon = $path;
        $bilan->save();

        return back()->with('success', 'Bilan modifié avec succès.');
    }

    // Supprimer bilan
    public function delete_bilan($id)
    {
        $bilan = TypeBilan::find($id);
        $bilan->isdelete = true;
        $bilan->save();
        return back()->with('success', 'Bilan supprimé avec succès.');
    }

    // Activer/Desactiver bilan
    public function toggle_bilan($id, $action)
    {
        $bilan = TypeBilan::find($id);
        $bilan->isactive = $action == 'activer' ? true : false;
        $bilan->save();
        return back()->with('success', 'Statut modifié avec succès.');
    }

    //GESTION DES COMMANDEs
    public function commandes(Request $request)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        if(!$laboratoire) {
            return redirect()->route('laboratoire.login')->with('error', 'Aucun laboratoire associé.');
        }

        // Filtrer par statut
        $view_type = $request->get('view', 'en_cours');

        // GROUPER PAR CODE : Une seule ligne par commande
        $subQuery = Commande::select('code')
            ->where('isdelete', false)
            ->where(function($q) use ($laboratoire) {
                $q->whereHas('examen', function($query) use ($laboratoire) {
                    $query->where('laboratorie_id', $laboratoire->id);
                })->orWhereHas('type_bilan', function($query) use ($laboratoire) {
                    $query->where('laboratorie_id', $laboratoire->id);
                });
            });

        if($view_type == 'historique') {
            $subQuery->whereIn('statut', ['Terminer', 'Annuler']);
        } else {
            $subQuery->whereNotIn('statut', ['Terminer', 'Annuler']);
        }

        $codes = $subQuery->distinct()->pluck('code');

        // Récupérer les commandes groupées avec informations agrégées
        $commandes = Commande::select(
            'code',
            DB::raw('MIN(id) as first_id'), // Pour accéder aux détails
            DB::raw('MAX(created_at) as created_at'),
            DB::raw('MAX(date_prelevement) as date_prelevement'),
            DB::raw('MAX(adress) as adress'),
            DB::raw('MAX(client_id) as client_id'),
            DB::raw('MAX(agent_id) as agent_id'),
            DB::raw('COUNT(*) as nombre_analyses'),
            DB::raw('MAX(statut) as statut')
        )
            ->whereIn('code', $codes)
            ->where('isdelete', false)
            ->groupBy('code')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        // Charger les relations pour chaque commande groupée
        $commandes->getCollection()->transform(function ($item) {
            $item->client = User::find($item->client_id);
            $item->agent = User::find($item->agent_id);

            // Compter les résultats uploadés
            $sous_commandes = Commande::where('code', $item->code)->get();
            $item->resultats_count = $sous_commandes->filter(function($sc) {
                return $sc->resultat && $sc->resultat->pdf_url;
            })->count();

            // Calculer le prix total
            $item->prix_total = $sous_commandes->sum(function($sc) {
                if($sc->examen) return $sc->examen->price;
                if($sc->type_bilan) return $sc->type_bilan->price;
                return 0;
            });

            return $item;
        });

        // Statistiques pour les badges
        $stats_commandes = [
            'en_cours' => Commande::select('code')
                ->where('isdelete', false)
                ->whereNotIn('statut', ['Terminer', 'Annuler'])
                ->where(function($q) use ($laboratoire) {
                    $q->whereHas('examen', function($query) use ($laboratoire) {
                        $query->where('laboratorie_id', $laboratoire->id);
                    })->orWhereHas('type_bilan', function($query) use ($laboratoire) {
                        $query->where('laboratorie_id', $laboratoire->id);
                    });
                })
                ->distinct('code')
                ->count(DB::raw('DISTINCT code')),
            'terminees' => Commande::select('code')
                ->where('isdelete', false)
                ->where('statut', 'Terminer')
                ->where(function($q) use ($laboratoire) {
                    $q->whereHas('examen', function($query) use ($laboratoire) {
                        $query->where('laboratorie_id', $laboratoire->id);
                    })->orWhereHas('type_bilan', function($query) use ($laboratoire) {
                        $query->where('laboratorie_id', $laboratoire->id);
                    });
                })
                ->distinct('code')
                ->count(DB::raw('DISTINCT code')),
        ];

        return view('laboratoire.commandes.index', compact('user_auth', 'laboratoire', 'commandes', 'view_type', 'stats_commandes'));
    }

    /**
     * Détails d'une commande
     */
    public function commande_details($id)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        // Récupérer la première commande du code pour avoir les infos générales
        $commande = Commande::with(['client', 'agent'])
            ->where('id', $id)
            ->where('isdelete', false)
            ->first();

        if(!$commande) {
            return back()->with('error', 'Commande introuvable.');
        }

        // Récupérer TOUTES les sous-commandes liées au même code pour vérifier l'accès
        $all_commandes = Commande::with(['examen', 'type_bilan', 'resultat'])
            ->where('code', $commande->code)
            ->where('isdelete', false)
            ->get();

        // Vérifier que cette commande appartient bien à ce laboratoire
        $belongs_to_lab = false;
        foreach($all_commandes as $sc) {
            if($sc->examen && $sc->examen->laboratorie_id == $laboratoire->id) {
                $belongs_to_lab = true;
                break;
            }
            if($sc->type_bilan && $sc->type_bilan->laboratorie_id == $laboratoire->id) {
                $belongs_to_lab = true;
                break;
            }
        }

        if(!$belongs_to_lab) {
            return back()->with('error', 'Vous n\'avez pas accès à cette commande.');
        }

        // Filtrer pour ne montrer QUE les examens/bilans de CE laboratoire
        $sous_commandes = $all_commandes->filter(function($sc) use ($laboratoire) {
            if($sc->examen && $sc->examen->laboratorie_id == $laboratoire->id) {
                return true;
            }
            if($sc->type_bilan && $sc->type_bilan->laboratorie_id == $laboratoire->id) {
                return true;
            }
            return false;
        });

        return view('laboratoire.commandes.details', compact('user_auth', 'laboratoire', 'commande', 'sous_commandes'));
    }

    /**
     * Upload résultat PDF pour une commande
     */
    public function upload_resultat(Request $request, $commande_id)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        $commande = Commande::findOrFail($commande_id);

        // Vérifier labo
        $belongs_to_lab = false;
        if($commande->examen && $commande->examen->laboratorie_id == $laboratoire->id) {
            $belongs_to_lab = true;
        }
        if($commande->type_bilan && $commande->type_bilan->laboratorie_id == $laboratoire->id) {
            $belongs_to_lab = true;
        }

        if(!$belongs_to_lab) {
            return back()->with('error', 'Vous n\'avez pas accès à cette commande.');
        }

        if(!$commande->agent_id) {
            return back()->with('error', 'Impossible d\'uploader le résultat. Cette commande n\'a pas encore d\'agent assigné.');
        }

        $request->validate([
            'pdf_url' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        // Récupérer ou créer le résultat
        $resultat = Resultat::where('commande_id', $commande->id)->first();

        if(!$resultat) {
            $resultat = Resultat::where('code_commande', $commande->code)
                ->whereNull('commande_id')
                ->first();
        }

        $pdf_path = "resultat_default.pdf";
        // Générer TOUJOURS un code automatiquement (cryptage obligatoire)
        $password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
        
        if($request->hasFile('pdf_url')) {
            if($resultat && $resultat->pdf_url && $resultat->pdf_url != 'resultat_default.pdf') {
                $old_pdf = public_path($resultat->pdf_url);
                if(file_exists($old_pdf)) {
                    unlink($old_pdf);
                }
            }

            $pdf_name = time() . '_' . $commande->code . '_' . $commande->id . '.pdf';
            $request->pdf_url->move(public_path() . "/resultat", $pdf_name);
            $pdf_path = "resultat/" . $pdf_name;

            // Crypter le PDF avec le mot de passe
            $full_path = public_path($pdf_path);
            $repository = new ResultatRepository(new \App\Models\Resultat());
            try {
                $repository->protectPdf($full_path, $full_path, $password);
                Log::info('PDF crypté avec succès: ' . $pdf_path . ' avec mot de passe: ' . $password);
            } catch (\Exception $e) {
                Log::error('Erreur lors du cryptage PDF: ' . $e->getMessage());
                return back()->with('error', 'Erreur lors du cryptage du PDF.');
            }
        }


        $isUpdate = false;
        if($resultat) {
            $isUpdate = true;
            $resultat->pdf_url = $pdf_path;
            $resultat->commande_id = $commande->id;
            $resultat->pdf_password = $password;
            $resultat->save();
        } else {
            $resultat = new Resultat();
            $resultat->code_commande = $commande->code;
            $resultat->commande_id = $commande->id;
            $resultat->pdf_url = $pdf_path;
            $resultat->pdf_password = $password;
            $resultat->save();
        }

        // --- ENVOI EMAIL AUTOMATIQUE AU PATIENT AVEC LE CODE ---
        try {
            $client = User::find($commande->client_id);
            if ($client && $client->email) {
                $clientName = $client->firstname . ' ' . $client->lastname;
                Mail::to($client->email)->send(new \App\Mail\ResultatDisponibleMail(
                    $commande,
                    $password,
                    $clientName
                ));
                Log::info('Email code PDF envoyé à: ' . $client->email . ' - Code: ' . $password);
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email code PDF: ' . $e->getMessage());
        }

        // --- NOTIFICATION PUSH AU CLIENT ---
        try {
            $client = User::find($commande->client_id);
            if ($client) {
                $client->notify(new SendPushNotification(
                    'Résultat Disponible ',
                    'Votre résultat est prêt ! Code de déchiffrement: ' . $password,
                    '/user/details/commande/' . $commande->code,
                    'Voir le résultat'
                ));
            }
        } catch (\Exception $e) {
            Log::error('Erreur Push Upload: ' . $e->getMessage());
        }

        // --- NOTIFICATION AUX ADMINS POUR NOUVEAU RÉSULTAT ---
        try {
            $admins = User::whereHas('role', function($query) {
                $query->whereIn('label', ['admin', 'admin Sup']);
            })->get();

            $laboratoire = auth()->user()->laboratorie;
            $laboName = $laboratoire ? $laboratoire->name : 'Un laboratoire';

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\CommandeNotification(
                    'Nouveau Résultat',
                    $laboName . ' a uploadé un résultat pour la commande #' . $commande->code
                ));
            }
        } catch (\Exception $e) {
            Log::error('Erreur notification admin nouveau résultat: ' . $e->getMessage());
        }

        $message = $isUpdate
            ? 'Résultat modifié et crypté avec succès ! Code envoyé par email au patient. Code: ' . $resultat->pdf_password
            : 'Résultat uploadé et crypté avec succès ! Code envoyé par email au patient. Code: ' . $resultat->pdf_password;

        return back()->with('success', $message);
    }

    /**
     * Upload batch résultats pour plusieurs analyses à la fois
     */
    public function upload_batch_resultat(Request $request)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        $request->validate([
            'code_commande' => 'required|string',
            'commande_ids' => 'required|array|min:1',
            'commande_ids.*' => 'exists:commandes,id',
            'pdf_url' => 'required|mimes:pdf|max:10240',
        ]);

        $commande_ids = $request->commande_ids;
        $code_commande = $request->code_commande;

        $commandes = Commande::with(['examen', 'type_bilan'])
            ->whereIn('id', $commande_ids)
            ->where('code', $code_commande)
            ->where('isdelete', false)
            ->get();

        if($commandes->count() !== count($commande_ids)) {
            return back()->with('error', 'Une ou plusieurs commandes sont invalides.');
        }

        foreach($commandes as $cmd) {
            $belongs_to_lab = false;
            if($cmd->examen && $cmd->examen->laboratorie_id == $laboratoire->id) $belongs_to_lab = true;
            if($cmd->type_bilan && $cmd->type_bilan->laboratorie_id == $laboratoire->id) $belongs_to_lab = true;

            if(!$belongs_to_lab) return back()->with('error', 'Vous n\'avez pas accès à certaines commandes.');
            if(!$cmd->agent_id) return back()->with('error', 'Pas d\'agent assigné.');
        }

        if(!$request->hasFile('pdf_url')) {
            return back()->with('error', 'Fichier PDF manquant.');
        }

        $pdf_name = time() . '_' . $code_commande . '_batch.pdf';
        $request->pdf_url->move(public_path() . "/resultat", $pdf_name);
        $pdf_path = "resultat/" . $pdf_name;

        // Générer TOUJOURS un code automatiquement (cryptage obligatoire)
        $pdf_password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

        // Encrypt the uploaded PDF
        $full_path = public_path($pdf_path);
        $repository = new ResultatRepository(new \App\Models\Resultat());
        try {
            $repository->protectPdf($full_path, $full_path, $pdf_password);
            Log::info('PDF batch crypté avec succès: ' . $pdf_path . ' avec mot de passe: ' . $pdf_password);
        } catch (\Exception $e) {
            Log::error('Erreur lors du cryptage PDF batch: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du cryptage du PDF.');
        }

        $created_count = 0;
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
                $created_count++;
            }
        }

        $client = $commandes->first()->client;
        if($client && $client->email) {
            try {
                $clientName = $client->firstname . ' ' . $client->lastname;
                $commandeInfo = $commandes->first(); 
                Mail::to($client->email)->send(new \App\Mail\ResultatDisponibleMail(
                    $commandeInfo,
                    $pdf_password,
                    $clientName
                ));
                Log::info('Email code PDF batch envoyé à: ' . $client->email . ' - Code: ' . $pdf_password);
            } catch (\Exception $e) {
                Log::error('Erreur envoi email code PDF batch: ' . $e->getMessage());
            }
        }

        if($client) {
            try {
                // Notif Push avec le code
                $client->notify(new SendPushNotification(
                    'Résultats Disponibles',
                    'Vos résultats sont prêts ! Code de déchiffrement: ' . $pdf_password,
                    '/user/details/commande/' . $code_commande,
                    'Voir les résultats'
                ));
            } catch (\Exception $e) {
                Log::error('Erreur notification client: ' . $e->getMessage());
            }
        }

        // --- NOTIFICATION AUX ADMINS POUR NOUVEAUX RÉSULTATS (BATCH) ---
        try {
            $admins = User::whereHas('role', function($query) {
                $query->whereIn('label', ['admin', 'admin Sup']);
            })->get();

            $laboratoire = auth()->user()->laboratorie;
            $laboName = $laboratoire ? $laboratoire->name : 'Un laboratoire';
            $analyses_count = $commandes->count();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\CommandeNotification(
                    'Nouveaux Résultats',
                    $laboName . ' a uploadé ' . $analyses_count . ' résultat(s) pour la commande #' . $code_commande
                ));
            }
        } catch (\Exception $e) {
            Log::error('Erreur notification admin nouveaux résultats batch: ' . $e->getMessage());
        }

        $analyses_count = $commandes->count();
        $message = "Résultats uploadés et cryptés avec succès pour {$analyses_count} analyse(s) ! Code envoyé par email au patient. Code: {$pdf_password}";

        return back()->with('success', $message);
    }



    public function delete_resultat($id)
    {
        $commande = Commande::with(['examen.laboratorie', 'type_bilan.laboratorie', 'resultat'])->findOrFail($id);

        $user_labo_id = auth()->user()->laboratorie_id;
        $commande_labo_id = $commande->examen ? $commande->examen->laboratorie_id : $commande->type_bilan->laboratorie_id;

        if($user_labo_id != $commande_labo_id) {
            abort(403, 'Accès non autorisé');
        }

        if(!$commande->resultat) {
            return back()->with('error', 'Aucun résultat à supprimer pour cette commande.');
        }

        if($commande->resultat->pdf_url && $commande->resultat->pdf_url != 'resultat_default.pdf') {
            $pdf_path = public_path($commande->resultat->pdf_url);
            if(file_exists($pdf_path)) {
                unlink($pdf_path);
            }
        }

        $commande->resultat->delete();

        $commande->statut = 'En cours';
        $commande->save();

        return back()->with('success', 'Résultat supprimé avec succès ! La commande est de nouveau "En cours".');
    }

    /**
     * Terminer officiellement une commande (passage à statut Terminer)
     */
    public function terminer_commande(Request $request, $code)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        $sous_commandes = Commande::with(['examen', 'type_bilan', 'resultat'])
            ->where('code', $code)
            ->where('isdelete', false)
            ->get();

        if($sous_commandes->isEmpty()) {
            return back()->with('error', 'Commande introuvable.');
        }

        $belongs_to_lab = false;
        foreach($sous_commandes as $sc) {
            if($sc->examen && $sc->examen->laboratorie_id == $laboratoire->id) $belongs_to_lab = true;
            if($sc->type_bilan && $sc->type_bilan->laboratorie_id == $laboratoire->id) $belongs_to_lab = true;
        }

        if(!$belongs_to_lab) {
            return back()->with('error', 'Vous n\'avez pas accès à cette commande.');
        }

        // Filtrer pour ne traiter QUE les examens/bilans de CE laboratoire
        $lab_commandes = $sous_commandes->filter(function($sc) use ($laboratoire) {
            if($sc->examen && $sc->examen->laboratorie_id == $laboratoire->id) return true;
            if($sc->type_bilan && $sc->type_bilan->laboratorie_id == $laboratoire->id) return true;
            return false;
        });

        $resultats_count = $lab_commandes->filter(fn($sc) => $sc->resultat && $sc->resultat->pdf_url)->count();
        if($resultats_count == 0) {
            return back()->with('error', 'Impossible de terminer : aucun résultat n\'a été uploadé pour vos analyses.');
        }

        $commissionService = app(\App\Services\CommissionService::class);
        foreach($lab_commandes as $sc) {
            if($sc->statut != 'Terminer') {
                $sc->statut = 'Terminer';
                $sc->save();

                $commissionService->creditForCommande($sc);
            }
        }

        
        $all_commandes = Commande::where('code', $code)->where('isdelete', false)->get();
        $all_terminated = $all_commandes->every(fn($sc) => $sc->statut == 'Terminer');

    
        $client = User::find($sous_commandes->first()->client_id);

        if($client) {
            try {
                if($all_terminated) {
                    $client->notify(new SendPushNotification(
                        'Commande Terminée 🎉',
                        'Vos analyses pour la commande #' . $code . ' sont terminées. Merci de votre confiance !',
                        '/user/details/commande/' . $code,
                        'Voir résultats'
                    ));
                } else {
                    $client->notify(new SendPushNotification(
                        'Résultats Disponibles',
                        'Une partie de vos analyses pour la commande #' . $code . ' est disponible (' . $laboratoire->name . ').',
                        '/user/details/commande/' . $code,
                        'Voir'
                    ));
                }
            } catch (\Exception $e) {
                Log::error('Erreur Push Terminer Commande: ' . $e->getMessage());
            }
        }

        $success_msg = $all_terminated 
            ? 'Commande entièrement terminée ! Le client peut accéder à tous les résultats.'
            : 'Vos analyses sont terminées ! Les autres laboratoires doivent encore compléter leurs résultats.';

        return back()->with('success', $success_msg);
    }
}
