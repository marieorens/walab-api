<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLabRegistrationNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $labo;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $labo)
    {
        $this->user = $user;
        $this->labo = $labo;
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
        return (new MailMessage)
                    ->subject('Nouvelle inscription laboratoire - Validation requise')
                    ->greeting('Bonjour Administrateur,')
                    ->line('Un nouveau laboratoire s\'est inscrit sur la plateforme WALAB.')
                    ->line('**Détails du laboratoire :**')
                    ->line('Nom : ' . $this->labo->name)
                    ->line('Adresse : ' . $this->labo->address)
                    ->line('Description : ' . ($this->labo->description ?? 'Non spécifiée'))
                    ->line('**Détails du responsable :**')
                    ->line('Nom : ' . $this->user->firstname . ' ' . $this->user->lastname)
                    ->line('Email : ' . $this->user->email)
                    ->line('Téléphone : ' . $this->user->phone)
                    ->line('**Statut :** Email non vérifié - En attente de validation email puis admin.')
                    ->action('Voir les détails', url('/admin/laboratoires'))
                    ->line('Veuillez valider ce laboratoire une fois l\'email vérifié.')
                    ->salutation('Cordialement, Équipe WALAB');
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
