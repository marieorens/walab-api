<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Practitioner;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register_web(Request $request)
    {
        $path = "";
        if($request->url_profil)
        {
            $image_url = time() . $request->url_profil->getClientOriginalName();
            $path = $request->url_profil->move(public_path() . "/profile", $image_url);
            $path = "profile/" . $image_url;
        }

        $user = $this->user->create([
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
            'role_id' => isset($request->role_id) ? $request->role_id : null, 
            'password' => Hash::make(isset($request->phone) ? $request->phone : 12345678),
            // 'status' => isset($request->status) ? $request->status : null,
        
        ]);

        return $user;

    }

    public function update_web(Request $request)
    {

        $path = "";
        if($request->url_profil)
        {
            $image_url = time() . $request->url_profil->getClientOriginalName();
            $path = $request->url_profil->move(public_path() . "/profile", $image_url);
            $path = "profile/" . $image_url;
        }
        else{
            $path = $this->user->url_profil;
        }

        $user = $this->user->update([
            'firstname' => isset($request->firstname) ? $request->firstname : null,
            'lastname' => isset($request->lastname) ? $request->lastname : null,
            'email' => isset($request->email) ? $request->email : null,
            'gender' => isset($request->gender) ? $request->gender : null,
            'city' => isset($request->city) ? $request->city : null,
            'date_naissance' => isset($request->date_naissance) ? $request->date_naissance : null,
            'adress' => isset($request->adress) ? $request->adress : null,
            'phone' => isset($request->phone) ? $request->phone : null,
            'url_profil' =>  $path,
            'role_id' => isset($request->role_id) ? $request->role_id : null, 
        ]);

        return $user;

    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Retirer les champs qui ne sont pas dans la table users
        $userType = $validated['user_type'] ?? 'client';
        $orderNumber = $validated['order_number'] ?? null;
        $profession = $validated['profession'] ?? null;
        $otherProfession = $validated['other_profession'] ?? null;
        $certificateFile = $request->file('certificate'); // Garder le fichier séparément
        
        // Si "other" est sélectionné et other_profession est fourni, utiliser la profession personnalisée
        if ($profession === 'other' && $otherProfession) {
            $profession = $otherProfession;
        }
        
        unset($validated['user_type']);
        unset($validated['order_number']);
        unset($validated['profession']);
        unset($validated['other_profession']);
        unset($validated['certificate']); // Retirer le champ certificate des données user

        $validated['password'] = Hash::make($validated['password']);

        // Définir le role_id selon le type d'utilisateur
        if ($userType === 'professionnel') {
            $practitionerRole = Role::where('label', 'practitioner')->first();
            $validated['role_id'] = $practitionerRole ? $practitionerRole->id : 3; // 3 = client par défaut
        } else {
            $validated['role_id'] = 3; // Client par défaut
        }

        // Créer l'utilisateur
        $user = $this->user->create($validated);

        // Si c'est un professionnel, créer l'entrée practitioner
        if ($userType === 'professionnel' && $orderNumber && $profession) {
            $certificatePath = null;
            
            // Gérer l'upload du certificat
            if ($certificateFile) {
                $certificateName = time() . '_' . $user->id . '_certificate.' . $certificateFile->getClientOriginalExtension();
                $certificatePath = $certificateFile->storeAs('certificates', $certificateName, 'public');
            }

            Practitioner::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'profession' => $profession,
                'certificate_url' => $certificatePath,
                'verification_status' => 'pending',
                'profile_completion' => 20, // Base completion
            ]);
        }

        // Envoyer l'email de vérification
        try {
            Log::info('Envoi email vérification inscription', [
                'email' => $user->email,
                'user_id' => $user->id,
                'user_type' => $userType,
                'mail_driver' => config('mail.default')
            ]);
            
            $user->notify(new VerifyEmailNotification());
            
            Log::info(' Email vérification envoyé', [
                'email' => $user->email,
                'user_id' => $user->id
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email vérification inscription', [
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $token = $user->createToken($user->email . '-AuthToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => $user,
            'message' => $userType === 'professionnel' 
                ? 'Inscription réussie. Votre compte sera validé par un administrateur.' 
                : 'Utilisateur inscrit avec succès'
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = $this->user->where('email', $validated['email'])->first();
        if (!$user || !Auth::attempt($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ]);
        }

        // Si email non vérifié, demander OTP
        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vérifier votre adresse email.',
                'requires_verification' => true,
                'email' => $user->email
            ]);
        }

        // Pour les praticiens et labs, vérifier le statut admin
        if (in_array($user->role_id, [5, 6])) { // 5: lab, 6: practitioner
            if ($user->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre compte est en attente de validation par un administrateur.'
                ]);
            }
        }

        $token = $user->createToken($user->email . '-AuthToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => $user,
            'message' => 'Utilisateur connecté'
        ]);
    }

    public function logout()
    {
        // Auth::logout();
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur déconnecté'
        ]);
    }
}
