<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Examen\TypeBilanRequest;
use App\Http\Requests\Examen\TypeBilanUpdateRequest;
use App\Models\Laboratorie;
use App\Models\TypeBilan;
use App\Models\User;
use App\Repository\TypeBilanRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class BilanController extends Controller
{
    /**
     * @var TypeBilanRepository
     */
    private $typeBilanRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(TypeBilanRepository $typeBilanRepository, AuthManager $auth)
    {
        $this->typeBilanRepository = $typeBilanRepository;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $bilans = TypeBilan::where('isdelete', false)->paginate(5);
        $laboratories = Laboratorie::Select("id", "name")->get();
        // $alerts = Alert::where('read', false)->get();
        return view('bilan.index', compact('user_auth', 'bilans', 'laboratories'));
    }

    public function lab_bilan(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $bilans = TypeBilan::where('laboratorie_id', $id)->where('isdelete', false)->paginate(5);
        $laboratorie = Laboratorie::find($id);
        return view('bilan.index', compact('user_auth', 'bilans', 'laboratorie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        $laboratories = Laboratorie::Select("id", "name")->get();
        return view('bilan.view', compact('user_auth', 'view', 'laboratories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeBilanRequest $request)
    {
        try{

            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;

            $bilan = $this->typeBilanRepository->create_TypeBilan($request);
            $message = "Type Bilan a été créé avec succès.";
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la création du Type Bilan");
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
        $bilan = TypeBilan::where("id", $id)->first();
        return view('bilan.view', compact('user_auth', 'view', 'bilan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        $laboratories = Laboratorie::Select("id", "name")->get();
        $bilan = TypeBilan::where("id", $id)->first();
        return view('bilan.view', compact('user_auth', 'view', 'bilan', 'laboratories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeBilanUpdateRequest $request, string $id)
    {
        try{
            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;
            $bilan = $this->typeBilanRepository->update_TypeBilan($request, $id);
            $message = "Type Bilan a été modifié avec succès.";
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la modification du Type Bilian");
        }
        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $type = TypeBilan::where("id", $id)->first();
            $type->isdelete = true;
            $type->save();
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suppression du Type Bilan");
        }
        return back()->with('success', "Type Bilan a été supprimé avec succès.");
    }

    public function active(string $id)
    {
        try{
            $type = TypeBilan::where("id", $id)->first();
            if($type->isactive){
                $type->isactive = false;
                $message = "Le type Bilan a été désactivé avec succès.";
            }else{
                $type->isactive = true;
                $message = "Le type Bilan a été activé avec succès.";
            }
            $type->save();
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suppression du Type Bilan");
        }
        return back()->with('success', $message);
    }
}
