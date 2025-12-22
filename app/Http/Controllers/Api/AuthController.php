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

/**
 * @OA\Tag(
 *     name="Authentification",
 *     description="Gestion de la connexion, inscription et déconnexion."
 * )
 */
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
            'city' => isset($request->city) ? $request->city : null,
            'date_naissance' => isset($request->date_naissance) ? $request->date_naissance : null,
            'adress' => isset($request->adress) ? $request->adress : null,
            'phone' => isset($request->phone) ? $request->phone : null,
            'url_profil' =>  $path,
            'role_id' => isset($request->role_id) ? $request->role_id : null,
            'password' => Hash::make(isset($request->phone) ? $request->phone : 12345678),
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

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Inscription d'un nouvel utilisateur",
     *     tags={"Authentification"},
     *     description="Permet d'inscrire un Patient ou un Professionnel de santé.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"firstname", "lastname", "email", "password", "phone", "user_type"},
     *             @OA\Property(property="firstname", type="string", example="Jean"),
     *             @OA\Property(property="lastname", type="string", example="Dupont"),
     *             @OA\Property(property="email", type="string", format="email", example="jean.dupont@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123!"),
     *             @OA\Property(property="phone", type="string", example="+22901020304"),
     *             @OA\Property(property="adress", type="string", example="Cotonou, Quartier Jak"),
     *             @OA\Property(property="ville", type="string", example="Cotonou"),
     *             @OA\Property(property="gender", type="string", enum={"Masculin", "Féminin"}, example="Masculin"),
     *             @OA\Property(property="date_naissance", type="string", format="date", example="1995-05-20"),
     *             @OA\Property(property="user_type", type="string", enum={"client", "professionnel"}, example="client", description="Type de compte"),
     *             @OA\Property(property="order_number", type="string", example="MED-8392", description="Requis si user_type = professionnel"),
     *             @OA\Property(property="profession", type="string", example="Médecin Généraliste", description="Requis si user_type = professionnel")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inscription réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Utilisateur inscrit avec succès"),
     *             @OA\Property(property="token", type="string", example="1|AbCdEf123..."),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation (Email existant, champs manquants...)"
     *     )
     * )
     */
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

        if ($userType === 'professionnel') {
            $practitionerRole = Role::where('label', 'practitioner')->first();
            $validated['role_id'] = $practitionerRole ? $practitionerRole->id : 3;
        } else {
            $validated['role_id'] = 3;
        }

        $user = $this->user->create($validated);

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
                'profile_completion' => 20,
            ]);
        }

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

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion utilisateur",
     *     tags={"Authentification"},
     *     description="Authentification par email et mot de passe.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="client@walab.bj"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Résultat de la tentative de connexion",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     description="Connexion réussie",
     *                     @OA\Property(property="success", type="boolean", example=true),
     *                     @OA\Property(property="message", type="string", example="Utilisateur connecté"),
     *                     @OA\Property(property="token", type="string", example="2|XyZaBc..."),
     *                     @OA\Property(property="data", type="object", ref="#/components/schemas/User")
     *                 ),
     *                 @OA\Schema(
     *                     description="Email non vérifié ou compte inactif",
     *                     @OA\Property(property="success", type="boolean", example=false),
     *                     @OA\Property(property="message", type="string", example="Veuillez vérifier votre adresse email."),
     *                     @OA\Property(property="requires_verification", type="boolean", example=true),
     *                     @OA\Property(property="email", type="string", example="user@mail.com")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants incorrects (si le code retourne 401, sinon 200 avec success=false)"
     *     )
     * )
     */
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

        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vérifier votre adresse email.',
                'requires_verification' => true,
                'email' => $user->email
            ]);
        }

        if (in_array($user->role_id, [5, 6])) {
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

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     description="Révoque le token d'authentification actuel.",
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Utilisateur déconnecté")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     )
     * )
     */
    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur déconnecté'
        ]);
    }
}
