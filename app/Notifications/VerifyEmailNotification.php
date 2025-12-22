<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public $message;
    public $subject;
    private $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message = 'Bienvenue sur Walab ! Veuillez vérifier votre adresse email en utilisant le code ci-dessous.';
        $this->subject = 'Vérification de votre adresse email - Walab';
        $this->otp = new Otp();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        try {
            $otp = $this->otp->generate($notifiable->email, 'numeric', 6, 30); 
            return (new MailMessage)
                ->subject($this->subject)
                ->greeting('Bonjour ' . ($notifiable->firstname ?? 'Cher utilisateur') . ' !')
                ->line($this->message)
                ->line('Votre code de vérification est :')
                ->line('**' . $otp->token . '**')
                ->line('Ce code est valide pendant 30 minutes.')
                ->line('Si vous n\'avez pas créé de compte sur Walab, ignorez cet email.')
                ->salutation('L\'équipe Walab');
        } catch (\Exception $e) {
            Log::error('Erreur envoi email de vérification: ' . $e->getMessage(), [
                'email' => $notifiable->email,
            ]);
            throw $e;
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
