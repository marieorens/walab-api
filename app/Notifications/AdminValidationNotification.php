<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminValidationNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $role = $this->user->role_id == 5 ? 'Laboratoire' : 'Praticien';
        return (new MailMessage)
                    ->subject("Nouvelle inscription $role en attente de validation")
                    ->greeting('Bonjour Admin,')
                    ->line("Un nouveau $role s'est inscrit et a vérifié son email.")
                    ->line("Nom: {$this->user->firstname} {$this->user->lastname}")
                    ->line("Email: {$this->user->email}")
                    ->action('Valider le compte', url('/admin/validation'))
                    ->line('Veuillez vérifier et valider ce compte.');
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
