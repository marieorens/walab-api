<?php

namespace App\Http\Controllers\Web;

use App\Enum\StatutCommandeEnum;
use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Frais;
use App\Models\Resultat;
use App\Models\User;
use App\Repository\CommandeRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class HomeWebContoller extends Controller
{
     /**
     * @var CommandeRepository
     */
    private $commandeRepository;


    public function __construct(CommandeRepository $commandeRepository)
    {
        $this->commandeRepository = $commandeRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(15);
        $user_auth = User::Where('id', Auth::user()->id)->first();
        $home = true;
        $commandes = Commande::select('code', DB::raw('MAX(id) as id'), DB::raw('MAX(created_at) as created_at'),
        DB::raw('(SELECT MAX(c2.agent_id) FROM commandes c2 WHERE c2.code = commandes.code) as agent_id'),
        DB::raw('(SELECT MAX(c2.client_id) FROM commandes c2 WHERE c2.code = commandes.code) as client_id'),
        DB::raw('MAX(type) as type'), DB::raw('MAX(statut) as statut'), DB::raw('count(*) as total'), DB::raw('MAX(date_prelevement) as date_prelevement'),
        DB::raw('MAX(adress) as adress'))
            ->where('isdelete', false)
            ->where('statut', StatutCommandeEnum::PENDING)
            ->groupBy('code')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);

        $commandes->getCollection()->transform(function ($query) {
        // $commandes->transform(function ($query) {
            $query->client = User::where('id', $query->client_id)->first();
            $query->agent = User::where('id', $query->agent_id)->first();
            $query->resultat = Resultat::where('code_commande', $query->code)->first();
            return $query;
        });
        $count_client = User::where('isdelete', false)->where('role_id', 3)->count();
        $count_agent = User::where('isdelete', false)->where('role_id', 2)->count();
        $count_commande =  Commande::where('isdelete', false)->count();
        $count_resultat = Resultat::where('isdelete', false)->count();
        $currentWeekOrders = $this->commandeRepository->getCurrentWeekOrders();
        $lastWeekOrders = $this->commandeRepository->getLastWeekOrders();
        $totals = $this->commandeRepository->getTotals();
        $agents = User::where("role_id", 2)->Select("id", "firstname", "lastname")->get();
        return view('user.index', compact('users', 'user_auth', 'home', 'commandes', 'count_client', 'count_agent',
         'count_commande', 'count_resultat', 'currentWeekOrders', 'lastWeekOrders', 'totals', 'agents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function storeFrais(Request $request)
    {
        $request->validate([
            'frais' => 'required|numeric',
            'pourcentage' => 'nullable|numeric|min:0|max:100' // Validation du pourcentage
        ]);

        $frais = Frais::find(1);

        $pourcentage = $request->input('pourcentage',40); // 40 par défaut si vide

        if(!$frais){
            $frais = Frais::create([
                'nom' => 'Frais de deplacement',
                'frais' => $request->frais,
                'pourcentage_majoration' => $pourcentage
            ]);
        } else {
            $frais->frais = $request->frais;
            $frais->pourcentage_majoration = $pourcentage;
            $frais->save();
        }

        return back()->with('success', 'Frais et pourcentage mis à jour');

    }
}
