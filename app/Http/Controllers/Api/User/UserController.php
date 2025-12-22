<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Services\SendMailService;
class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * INFO user token
     */
    public function info_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        // $token = PersonalAccessToken::where('token', $request->token)->first();
        $token = PersonalAccessToken::findToken($request->token);
        if(!$token){
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'token invalid',
                'data' => null
            ]);
        }
        $this->user = $token->tokenable;

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'info user by token',
            'data' => $this->user
        ]);
    }

    /**
     * Notification Listes user
     */
    public function notify_user(Request $request)
    {
        
        $user = User::where('id', Auth::user()->id)->first();

        $notifications = $user->notifications;
        // auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'les notifications',
            'data' => $notifications
        ]);
    }

    /**
     * Notification masquer comme Lu
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $notification = auth()->user()->unreadNotifications->find($request->id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'la notification',
            'data' => $notification
        ]);
    }

    public function refresh_token_notify(Request $request){
        try {

            $validateUser = Validator::make($request->all(), 
            [
                'token' => 'required',

            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = Auth::user();
            $user = User::where('id', $user->id)->first();
            $token = $request->token;
            $user->update([
                'token_notify' => $token,
            ]);

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'la notification',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update_info(Request $request){
        try{

            $user = Auth::user();
            $validateUser = Validator::make($request->all(), 
            [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'gender' => ['string', 'in:male,female'],
                'city' => ['string', 'max:255'],
                'date_naissance' => ['required', 'string', 'max:255'],
                'adress' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string'],

            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::where('id', $user->id)->first();
            
            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'gender' => isset($request->gender) ? $request->gender : $user->gender,
                'phone' => isset($request->phone) ? $request->phone : $user->phone,
                'date_naissance' => isset($request->date_naissance) ? $request->date_naissance : $user->date_naissance,
                'city' => isset($request->city) ? $request->city : $user->city,
                'adress' => isset($request->adress) ? $request->adress : $user->adress,
            ]);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'modification user',
                'data' => $user
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
    public function set_profile(Request $request){

        try{

            $user = Auth::user();
            $validateUser = Validator::make($request->all(), 
            [
                'profile' => 'required|image',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::where('id', $user->id)->first();

            $image_url = time() . $request->profile->getClientOriginalName();
            $path = $request->profile->move(public_path() . "/profile", $image_url);
            $path = "profile/" . $image_url;

            $user->url_profil = $path;
            $user->save();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'image profile modifié',
                'data' => $user
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function modifyPassword(Request $request){
        try {

            $validateUser = Validator::make($request->all(), 
            [
                'password' => 'required',
                'new_password' => 'required',

            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = Auth::user();
            $user = User::where('id', $user->id)->first();
            $verify = Hash::check($request->password, $user->password);
            if (!$verify) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Mot de passe incorrect',
                ], 400);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'mot de passe modifié',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Envoie d'email au support
     */
    public function createEmailSupport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => ['string'],
            'prenom' => ['string'],
            'email' => ['email'],
            'numero' => ['string'],
            'message' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $message = "
            Bonjour,

            Nous avons reçu une demande de support de la part de {$request->Nom} {$request->Prénom}. Voici les détails fournis :

            - Nom : {$request->Nom}
            - Prénom : {$request->Prénom}
            - Email : {$request->Email}
            - Numéro : {$request->Numéro}

            Veuillez trouver ci-dessous le message envoyé par l'utilisateur :

            ---
            {$request->Message}
            ---

            Nous vous remercions de bien vouloir traiter cette demande dans les plus brefs délais.

            Cordialement,

            L'équipe de support
        ";

        $sendEmail = new SendMailService();
        $sendEmail->sendMailSupport($message);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Le message du support a été envoyé',
        ]);
    }


}
