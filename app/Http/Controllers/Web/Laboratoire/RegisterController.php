<?php

namespace App\Http\Controllers\Web\Laboratoire;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Laboratorie;
use App\Enum\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function create()
    {
        return view('laboratoire.auth.register');
    }

    public function store(Request $request)
    {
        // validation
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|min:8|confirmed',
            'lab_name' => 'required',
            'address' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'terms' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // upload image
            $path = "defaut_image.jpg";
            if($request->image){
                $image_url = time() . $request->image->getClientOriginalName();
                $request->image->move(public_path() . "/laboratoire", $image_url);
                $path = "laboratoire/" . $image_url;
            }

            // creer utilisateur
            $user = new User();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->role_id = 5; // role laboratoire
            $user->status = 'pending';
            $user->token_notify = '';
            $user->save();

            // creer laboratoire
            $labo = new Laboratorie();
            $labo->name = $request->lab_name;
            $labo->address = $request->address;
            $labo->image = $path;
            $labo->description = $request->description ?? '';
            $labo->user_id = $user->id;
            $labo->save();

            // Envoyer l'email de vérification
            try {
                $notification = new \App\Notifications\VerifyEmailNotification();
                $user->notify($notification);
                
                // En développement, stocker l'OTP dans la session pour les tests
                if (config('mail.default') === 'log') {
                    $otp = app(\Ichtrojan\Otp\Otp::class)->generate($user->email, 'numeric', 6, 30);
                    session(['dev_otp' => $otp->token, 'dev_email' => $user->email]);
                }
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email vérification lab: ' . $e->getMessage());
                // En développement, générer quand même l'OTP
                if (config('mail.default') === 'log') {
                    $otp = app(\Ichtrojan\Otp\Otp::class)->generate($user->email, 'numeric', 6, 30);
                    session(['dev_otp' => $otp->token, 'dev_email' => $user->email]);
                } else {
                    throw $e; // Re-throw in production
                }
            }

            DB::commit();

            // Notifier les admins de la nouvelle inscription
            $admins = User::whereIn('role_id', [1, 4])->get(); // 1: admin, 4: admin sup
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new \App\Notifications\NewLabRegistrationNotification($user, $labo));
                } catch (\Exception $e) {
                    Log::error('Erreur notification admin nouvelle inscription lab: ' . $e->getMessage());
                }
            }

            return redirect()->route('laboratoire.login')
                ->with('success', 'Inscription réussie ! Vérifiez votre email pour le code OTP.')
                ->with('requires_verification', true)
                ->with('email', $request->email);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration failed: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
        }
    }
}
