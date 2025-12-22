<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $user = $user_auth;
        $commandes = Commande::where('client_id', $user->id)->OrderBy('created_at', 'DESC')->limit(5)->get();
        return view('user.account', compact('user_auth', 'user', 'commandes'));
    }

    /**
     * Display a listing of the resource.
     */
    public function account(User $user)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $commandes = Commande::where('client_id', $user->id)->OrderBy('created_at', 'DESC')->limit(5)->get();
        return view('user.account', compact('user_auth', 'user', 'commandes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $roles = Role::all();
        $view = false;
        return view('user.view', compact('user_auth', 'view', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = true;
        $auth = new AuthController(new User);
        $user = $auth->register_web($request);
        return view('user.view', compact('user_auth', 'view', "user"));
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = true;
        $user = User::where("id", $id)->first();
        return view('user.view', compact('user_auth', 'view', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $roles = Role::all();
        $view = false;
        $user = User::where("id", $id)->first();
        return view('user.view', compact('user_auth', 'view', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $auth = new AuthController(new User);
        $user_upadte = $auth->update_web($request);
        $commandes = Commande::where('client_id', $user->id)->OrderBy('created_at', 'DESC')->limit(5);
        return view('user.account', compact('user_auth', 'user', 'commandes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_password(Request $request, User $user)
    {
        $validated = Validator::make($request->all(), [
            'password' => ['required|confirmed', 'string', 'min:8', 'max:255'],
        ]);

        $user_auth = User::where("id", Auth::user()->id)->first();
        // $user = User::where("id", $user->id)->first();
        $user->password = $request->password;
        $user->save();
        $commandes = Commande::where('client_id', $user->id)->OrderBy('created_at', 'DESC')->limit(5);
        return view('user.account', compact('user_auth', 'user', 'commandes'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // User::where("id", $id)->delete();

        return redirect()->route('home');
    }
}
