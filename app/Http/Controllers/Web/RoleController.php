<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $user_auth = User::where("id", Auth::user()->id)->first();
        $roles = Role::paginate(5);
        // $alerts = Alert::where('read', false)->get();
        return view('user.role.index', compact('user_auth', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = false;
        
        return view('user.role.view', compact('user_auth', 'view'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = true;
        $role = Role::create([
            'label' => $request->label,
            'value' => $request->value,
        ]);
        
        return view('user.role.view', compact('user_auth', 'view', "role"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = true;
        $role = Role::where('id', $id)->first();
        
        return view('user.role.view', compact('user_auth', 'view', 'role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = false;
        $role = Role::where('id', $id)->first();
       
        return view('user.role.view', compact('user_auth', 'view', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $view = true;
        $role = Role::where('id', $id)->first();
        $role->update([
            'label' => $request->label,
            'value' => $request->value,
        ]);
       
        return view('user.role.view', compact('user_auth', 'view', 'role'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            Role::where("id", $id)->first();
        }catch (\Exception $e){
            return back()->with('error', 'La suppression ne peut plus être effectué!');
        }
        return back()->with('success', 'Suppression  réussi !');
    }
}
