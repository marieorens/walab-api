<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\laboratoire;
use App\Models\Laboratorie;
use App\Models\User;
use App\Repository\LaboratorieRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class LaboratorieController extends Controller
{
    /**
     * @var LaboratorieRepository
     */
    private $laboratorieRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(LaboratorieRepository $laboratorieRepository, AuthManager $auth)
    {
        $this->laboratorieRepository = $laboratorieRepository;
        $this->auth = $auth;
    }

    
    public function index()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        
        $laboratoires = Laboratorie::where('isdelete', false)
            ->where(function($query) {
                $query->whereHas('user', function($q) {
                    $q->whereIn('status', ['active', 'suspended']);
                })
                ->orWhereNull('user_id');
            })
            ->paginate(5);
        
        $laboratoires_pending = Laboratorie::where('isdelete', false)
            ->whereHas('user', function($query) {
                $query->where('status', 'pending');
            })
            ->get();
        
        return view('user.admin.laboratoires.index', compact('user_auth', 'laboratoires', 'laboratoires_pending'));
    }

   
    public function create()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        return view('user.admin.laboratoires.view', compact('user_auth', 'view'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;
            $laboratoire = $this->laboratorieRepository->create_laboratorie($request);
            $message = "Le Laboratoire a été créé avec succès.";
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la création du Laboratoire.");
        }
        return back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = true;
        $laboratoire = Laboratorie::where("id", $id)->first();
        return view('user.admin.laboratoires.view', compact('user_auth', 'view', 'laboratoire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        $laboratoire = Laboratorie::where("id", $id)->first();
        return view('user.admin.laboratoires.view', compact('user_auth', 'view', 'laboratoire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;
            $laboratoire = $this->laboratorieRepository->update_laboratorie($request, $id);
            $message = "Le Laboratoire a été modifié avec succès.";
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la modification du Laboratoire.");
        }
        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $lab = Laboratorie::where("id", $id)->first();
            $lab->isdelete = true;
            $lab->save();
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suppression du Laboratoire.");
        }
        return back()->with('success', "Le Laboratoire a été supprimé avec succès.");
    }

    public function valider(string $id)
    {
        try{
            $lab = Laboratorie::where("id", $id)->first();
            if($lab && $lab->user){
                $lab->user->status = 'active';
                $lab->user->save();
            }
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue.");
        }
        return back()->with('success', "Le Laboratoire a été validé avec succès.");
    }

    /**
     * Suspendre un laboratoire (changer le statut à 'suspended')
     */
    public function suspendre(string $id)
    {
        try{
            $lab = Laboratorie::where("id", $id)->first();
            if($lab && $lab->user){
                $lab->user->status = 'suspended';
                $lab->user->save();
            }
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suspension.");
        }
        return back()->with('success', "Le Laboratoire a été suspendu avec succès.");
    }

    /**
     * Activer un laboratoire suspendu (changer le statut à 'active')
     */
    public function activer(string $id)
    {
        try{
            $lab = Laboratorie::where("id", $id)->first();
            if($lab && $lab->user){
                $lab->user->status = 'active';
                $lab->user->save();
            }
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de l'activation.");
        }
        return back()->with('success', "Le Laboratoire a été activé avec succès.");
    }
}
