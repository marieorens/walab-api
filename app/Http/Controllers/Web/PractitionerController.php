<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Practitioner;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PractitionerController extends Controller
{
    /**
     * Display a listing of practitioners.
     */
    public function index()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        
        // Praticiens validés (approuvés)
        $approvedPractitioners = Practitioner::with('user')
            ->where('verification_status', 'approved')
            ->orderBy('created_at', 'DESC')
            ->get();
        
        // Praticiens en attente de validation
        $pendingPractitioners = Practitioner::with('user')
            ->where('verification_status', 'pending')
            ->orderBy('created_at', 'ASC')
            ->get();
        
        return view('user.practitioner.index', compact('user_auth', 'approvedPractitioners', 'pendingPractitioners'));
    }

    /**
     * Show the form for creating a new practitioner.
     */
    public function create()
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $roles = Role::all();
        
        return view('user.practitioner.create', compact('user_auth', 'roles'));
    }

    /**
     * Store a newly created practitioner.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'order_number' => 'required|string|unique:practitioners,order_number',
            'profession' => 'required|string',
            'other_profession' => 'required_if:profession,other|string|max:255',
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'password' => 'required|min:8',
        ]);

        // Créer l'utilisateur
        $practitionerRole = Role::where('label', 'practitioner')->first();
        
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'city' => $request->city,
            'adress' => $request->adress,
            'date_naissance' => $request->date_naissance,
            'role_id' => $practitionerRole ? $practitionerRole->id : 6,
            'password' => Hash::make($request->password),
            'token_notify' => '',
        ]);

        // Déterminer la profession finale
        $finalProfession = $request->profession;
        if ($request->profession === 'other' && $request->other_profession) {
            $finalProfession = $request->other_profession;
        }

        // Gérer l'upload du certificat
        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificateFile = $request->file('certificate');
            $certificateName = time() . '_' . $user->id . '_certificate.' . $certificateFile->getClientOriginalExtension();
            $certificatePath = $certificateFile->storeAs('certificates', $certificateName, 'public');
        }

        // Créer le practitioner
        Practitioner::create([
            'user_id' => $user->id,
            'order_number' => $request->order_number,
            'profession' => $finalProfession,
            'certificate_url' => $certificatePath,
            'verification_status' => 'approved', // Approuvé par défaut par admin
            'profile_completion' => 20,
        ]);

        return redirect()->route('practitioner.index')->with('success', 'Praticien ajouté avec succès');
    }

    /**
     * Display the specified practitioner.
     */
    public function show($id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $practitioner = Practitioner::with('user')->findOrFail($id);
        
        return view('user.practitioner.show', compact('user_auth', 'practitioner'));
    }

    /**
     * Show the form for editing the specified practitioner.
     */
    public function edit($id)
    {
        $user_auth = User::where("id", Auth::user()->id)->first();
        $practitioner = Practitioner::with('user')->findOrFail($id);
        
        return view('user.practitioner.edit', compact('user_auth', 'practitioner'));
    }

    /**
     * Update the specified practitioner.
     */
    public function update(Request $request, $id)
    {
        $practitioner = Practitioner::with('user')->findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $practitioner->user_id,
            'phone' => 'required|string',
            'order_number' => 'required|string|unique:practitioners,order_number,' . $id,
            'profession' => 'required|string',
        ]);

        // Mettre à jour l'utilisateur
        $practitioner->user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'city' => $request->city,
            'adress' => $request->adress,
            'date_naissance' => $request->date_naissance,
        ]);

        // Mettre à jour le password si fourni
        if ($request->filled('password')) {
            $practitioner->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Mettre à jour le practitioner
        $practitioner->update([
            'order_number' => $request->order_number,
            'profession' => $request->profession,
        ]);

        return redirect()->route('practitioner.index')->with('success', 'Praticien modifié avec succès');
    }

    /**
     * Remove the specified practitioner.
     */
    public function destroy($id)
    {
        $practitioner = Practitioner::findOrFail($id);
        $user = $practitioner->user;
        
        $practitioner->delete();
        $user->delete();

        return redirect()->route('practitioner.index')->with('success', 'Praticien supprimé avec succès');
    }

    /**
     * Approve practitioner registration.
     */
    public function approve($id)
    {
        $practitioner = Practitioner::findOrFail($id);
        
        $practitioner->update([
            'verification_status' => 'approved',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        return redirect()->route('practitioner.index')->with('success', 'Praticien validé avec succès');
    }

    /**
     * Reject practitioner registration.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $practitioner = Practitioner::findOrFail($id);
        
        $practitioner->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        return redirect()->route('practitioner.index')->with('success', 'Praticien rejeté');
    }

    /**
     * Suspend/Activate practitioner.
     */
    public function toggleStatus($id)
    {
        $practitioner = Practitioner::with('user')->findOrFail($id);
        
        $newStatus = $practitioner->user->status === 'active' ? 'suspended' : 'active';
        
        $practitioner->user->update([
            'status' => $newStatus
        ]);

        $message = $newStatus === 'active' ? 'Praticien activé' : 'Praticien suspendu';
        
        return redirect()->route('practitioner.index')->with('success', $message);
    }
}
