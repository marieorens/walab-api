<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commande\CommandeRequest;
use App\Http\Requests\Commande\CommandeUpdateRequest;
use App\Models\ChatCommande;
use App\Models\Commande;
use App\Models\User;
use App\Repository\CommandeRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class CommandeChatController extends Controller
{
    /**
     * @var CommandeRepository
     */
    private $commandeRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(CommandeRepository $commandeRepository, AuthManager $auth)
    {
        $this->commandeRepository = $commandeRepository;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $commandes = Commande::where('isdelete', false)->OrderBy('created_at', 'DESC')->paginate(15);
        // $alerts = Alert::where('read', false)->get();
        return view('commande.index', compact('user_auth', 'commandes'));
    }


    /**
     * Display a listing of the resource assigne.
     */
    public function assigne()   
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $commandes = Commande::Where('agent_id', null)->OrderBy('created_at', 'DESC')->paginate(15);
        // $alerts = Alert::where('read', false)->get();
        return view('commande.index', compact('user_auth', 'commandes'));
    }
      
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        return view('commande.view', compact('user_auth', 'view'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommandeRequest $request)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = true;
        $commande = $this->commandeRepository->create_commande($request, $request->client_id);
        $message = "Commande créé avec Success";
        return view('commande.view', compact('user_auth', 'view', "commande", 'message'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = true;
        $commande = Commande::where("id", $id)->first();
        return view('commande.view', compact('user_auth', 'view', 'commande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        $commande = Commande::where("id", $id)->first();
        return view('commande.view', compact('user_auth', 'view', 'commande'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommandeUpdateRequest $request, string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = true;
        $commande = $this->commandeRepository->update_commande($request, $request->client_id);
        $message = "Commande modifié avec Success";
        return view('commande.view', compact('user_auth', 'view'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chat = ChatCommande::where("id", $id)->first();
        $chat->isdelete = true;
        $chat->save();

        return redirect()->route('home');
    }
}
