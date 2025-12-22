<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeNotification extends Notification
{
    use Queueable;

    protected $type;
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $type, string $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

   
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
        ];
    }

    // Structure de la notification pour le stockage en base de données
    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
        ];
    }
}
