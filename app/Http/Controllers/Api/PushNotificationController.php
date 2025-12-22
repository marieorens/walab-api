<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Models\User;
use App\Models\DaTaNotification;
 

class PushNotificationController extends Controller
{
    protected $notification;

    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }              
                
    public function notification(User $user, DaTaNotification $data)
    {
        $FcmToken = $user->token_notify;
        
        // Vérifier si le token existe et n'est pas vide
        if (empty($FcmToken)) {
            return; // si pas de token, pas de notification push (utilisateur web ou token non enregistré)
        }
        
        $title = $data->gettitre();
        $type = $data->gettype();
        $body =  $data->getbody();
        $id =  $data->getid();
        // $imageUrl = 
        
        try {
            $message = CloudMessage::fromArray([
              'token' => $FcmToken,
              'notification' => [
                'title' => $title,
                 'body' => $body,
                //  'image' => $imageUrl,
                ],
                'data' => [
                  'type' => $type,
                  'id' => $id,
                ],
             ]);
        
           $this->notification->send($message);
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas bloquer le processus
            \Log::warning('Failed to send push notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
