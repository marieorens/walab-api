<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Examen\ExamenRequest;
use App\Http\Requests\Examen\ExamenUpdateRequest;
use App\Models\Examen;
use App\Models\Laboratorie;
use App\Models\User;
use App\Repository\ExamenRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class ExamenController extends Controller
{

    /**
     * @var ExamenRepository
     */
    private $examenRepository;

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(ExamenRepository $examenRepository, AuthManager $auth)
    {
        $this->examenRepository = $examenRepository;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $examens = Examen::where('isdelete', false)->paginate(5);
        $laboratories = Laboratorie::Select("id", "name")->get();
        return view('examen.index', compact('user_auth', 'examens', 'laboratories'));
    }

    public function lab_examen(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $examens = Examen::where('laboratorie_id', $id)->where('isdelete', false)->paginate(5);
        $laboratorie = Laboratorie::find($id);
        return view('examen.index', compact('user_auth', 'examens', 'laboratorie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        $laboratories = Laboratorie::Select("id", "name")->get();
        return view('examen.view', compact('user_auth', 'view', 'laboratories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamenRequest $request)
    {
        try{
            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;
            $examen = $this->examenRepository->create_Examen($request);
            $message = "L'Examen a été créé avec succès.";
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la création de l'Examen.");
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
        $examen = Examen::where("id", $id)->first();
        return view('examen.view', compact('user_auth', 'view', 'examen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $view = false;
        $laboratories = Laboratorie::Select("id", "name")->get();
        $examen = Examen::where("id", $id)->first();
        return view('examen.view', compact('user_auth', 'view', 'examen', 'laboratories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamenUpdateRequest $request, string $id)
    {
        try{
            $user_auth = User::where("id", $this->auth->user()->id)->first();
            $view = true;
            $examen = $this->examenRepository->update_Examen($request, $id);
            $message = "L'Examen a été modifié avec succès.";
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la modification de l'Examen.");
        }
        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $examen = Examen::where("id", $id)->first();
            $examen->isdelete = true;
            $examen->save();
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suppression de l'Examen.");
        }
        return back()->with('success', "L'Examen a été supprimé avec succès.");
    }

    public function active(string $id)
    {
        try{
            $type = Examen::where("id", $id)->first();
            if($type->isactive){
                $type->isactive = false;
                $message = "L'Examen a été désactivé avec succès.";
            }else{
                $type->isactive = true;
                $message = "L'Examen a été activé avec succès.";
            }
            $type->save();
        }catch (\Exception $e){
            return back()->with('error', "Une erreur est survenue lors de la suppression du Type Bilan");
        }
        return back()->with('success', $message);
    }
}
