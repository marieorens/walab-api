<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResultatRequest;
use App\Models\Resultat;
use App\Models\User;
use App\Repository\ResultatRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResultatController extends Controller
{

    /**
     * @var ResultatRepository
     */
    private $resultatRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(ResultatRepository $resultatRepository, AuthManager $auth)
    {
        $this->resultatRepository = $resultatRepository;
        $this->auth = $auth;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $resultats = Resultat::where('isdelete', false)->paginate(5);
        // $alerts = Alert::where('read', false)->get();
        return view('resultat.index', compact('user_auth', 'resultats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;

        return view('resultat.view', compact('user_auth', 'view'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResultatRequest $request)
    {
        try{
            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;

            $resultat = $this->resultatRepository->create_Resultat($request);
            $message = "Le résultat a été créé avec succès.";
        }catch (\Exception $e){
            Log::error('Pdf securise : ' . $e->getMessage()
            );
            return back()->with('error', "Une erreur est survenue lors de la création du résultat.");
        }
        return back()->with('success', $message);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createResultat(ResultatRequest $request)
    {
        try{
            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;
            $resultat = $this->resultatRepository->create_Resultat($request);
        }catch (\Exception $e){
            Log::error('Pdf securise : ' . $e->getMessage()
            );
            return back()->with('error', "Une erreur est survenue lors de la création du résultat.");
        }
        return back()->with('success', "Le résultat a été créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = true;
        $resultat = Resultat::where('id', $id)->first();

        return view('resultat.view', compact('user_auth', 'view', 'resultat'));
    }

    public function resCommande(string $code)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = true;
        $resultat = Resultat::where('code_commande', $code)->first();

        return view('resultat.view', compact('user_auth', 'view', 'resultat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        $resultat = Resultat::where('id', $id)->first();

        return view('resultat.view', compact('user_auth', 'view', 'resultat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = true;
        $resultat = Resultat::where('id', $id)->first();
        $resultat = $this->resultatRepository->update_Resultat($request);

        return view('resultat.view', compact('user_auth', 'view', 'resultat'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $res = Resultat::where("id", $id)->first();
            $res->isdelete = true;
            $res->save();
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suppression du résultat.");
        }
        return back()->with('success', "Le résultat a été supprimé avec succès.");
    }
}
