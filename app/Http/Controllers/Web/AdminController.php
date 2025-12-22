<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RegisterUPRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $user_auth = User::where("id", Auth::user()->id)->first();
        $agents = User::where("role_id", 1)->where('isdelete', false)->paginate(5);
        // $alerts = Alert::where('read', false)->get();
        $roles = Role::all();
        return view('user.admin.index', compact('user_auth', 'agents', 'roles'));
    }

    /**
     * Display a listing of the resource.
     */
    public function bloquer(User $user)
    { 
        try{
            if($user->status == "active"){
                $user->status = "blocked";
                $user->save();
                return back()->with('success', 'L\'administrateur a été bloqué avec succès. Il ne pourra plus accéder aux fonctionnalités administratives tant que son accès ne sera pas rétabli.');
            }else{
                $user->status = "active";
                $user->save();
                return back()->with('success', 'L\'administrateur a été débloqué avec succès. Il a désormais de nouveau accès à toutes les fonctionnalités administratives.');
            }
            $user->save();
        }catch (\Exception $e){
            $message = "Une erreur s'est produite lors du processus. Veuillez réessayer plus tard.";
            return back()->with('error', $message);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $roles = Role::all();
        $view = false;
        return view('user.admin.view', compact('user_auth', 'view', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        try{
            $user_auth = User::where("id", Auth::user()->id)->first();
            $view = true;
            $path = "profile/1724018884profile.png";
            if($request->url_profil)
            {
                $image_url = time() . $request->url_profil->getClientOriginalName();
                $path = $request->url_profil->move(public_path() . "/profile", $image_url);
                $path = "profile/" . $image_url;
            }
            $agent = User::create([
                'firstname' => isset($request->firstname) ? $request->firstname : null,
                'lastname' => isset($request->lastname) ? $request->lastname : null,
                'email' => isset($request->email) ? $request->email : null,
                'gender' => isset($request->gender) ? $request->gender : null,
                // 'country' => isset($request->lastname) ? $request->lastname : null,
                'city' => isset($request->city) ? $request->city : null,
                'date_naissance' => isset($request->date_naissance) ? $request->date_naissance : null,
                'adress' => isset($request->adress) ? $request->adress : null,
                'phone' => isset($request->phone) ? $request->phone : null,
                'url_profil' =>  $path,
                'role_id' => isset($request->role_id) ? $request->role_id : 1, 
                'password' => Hash::make(isset($request->password) ? $request->password : $request->phone),
                // 'status' => isset($request->status) ? $request->status : null,
            ]);
            $roles = Role::all();
            $message = "L'utilisateur Admin a été créé avec succès.";
        }catch (\Exception $e){
            return back()->with('error',  "Une erreur est survenue lors de la création de l'utilisateur Admin.");
        }
        return back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = true;
        $agent = User::where("id", $id)->first();
        return view('user.admin.view', compact('user_auth', 'view', 'agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $roles = Role::all();
        $view = false;
        $agent = User::where("id", $id)->first();
        return view('user.admin.view', compact('user_auth', 'view', 'agent', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegisterUPRequest $request, string $id)
    {
        try{
            $user_auth = User::where("id", Auth::user()->id)->first();
            $view = true;
            $agent = User::where("id", $id)->first();
            if($request->url_profil)
            {
                $image_url = time() . $request->url_profil->getClientOriginalName();
                $path = $request->url_profil->move(public_path() . "/profile", $image_url);
                $path = "profile/" . $image_url;
            }
            else{
                $path = $agent->url_profil;   
            }
            $agent = $agent->update([
                'firstname' => isset($request->firstname) ? $request->firstname : null,
                'lastname' => isset($request->lastname) ? $request->lastname : null,
                'email' => isset($request->email) ? $request->email : null,
                'gender' => isset($request->gender) ? $request->gender : null,
                // 'country' => isset($request->lastname) ? $request->lastname : null,
                'city' => isset($request->city) ? $request->city : null,
                'date_naissance' => isset($request->date_naissance) ? $request->date_naissance : null,
                'adress' => isset($request->adress) ? $request->adress : null,
                'phone' => isset($request->phone) ? $request->phone : null,
                'url_profil' =>  $path,
                'role_id' => isset($request->role_id) ? $request->role_id : 1, 
                // 'status' => isset($request->status) ? $request->status : null,
            ]);
            $agent = User::where("id", $id)->first();
            $roles = Role::all();
            $message = "L'utilisateur Admin a été modifié avec succès.";
        }catch (\Exception $e){
            return back()->with('error',  "Une erreur est survenue lors de la modification de l'utilisateur Admin.");
        }
        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $admin = User::where("id", $id)->first();
            $admin->isdelete = true;
            $admin->email = $admin->email . time();
            $admin->save();
        }catch (\Exception $e){
            return back()->with('error',  "Une erreur est survenue lors de la suppression de l'utilisateur Admin.");
        }
        return back()->with('success', "L'utilisateur Admin a été supprimé avec succès.");
    }
}
