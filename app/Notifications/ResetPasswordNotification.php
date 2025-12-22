<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message = '"Utilisez le code ci-dessous pour réinitialiser votre mot de passe.';
        $this->subject = 'Réinitialisation de mot de passe';
        $this->fromEmail = 'test@gmail.com';
        $this->mailer = 'smtp';
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
            $otp = $this->otp->generate($notifiable->email, 'numeric', 6, 15);
            return (new MailMessage)
                ->mailer('smtp')
                ->subject($this->subject)
                ->line($this->message)
                ->line("code :" . $otp->token);
        } catch (\Exception $e) {
            // Log l'erreur
            Log::error('Error sending reset password email: ' . $e->getMessage(), [
                'notifiable' => $notifiable,
            ]);
    
            // Lancer une nouvelle exception si nécessaire
            // throw new \Exception('Failed to send reset password email.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
