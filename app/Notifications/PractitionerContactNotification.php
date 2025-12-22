<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PractitionerContactNotification extends Notification
{
    use Queueable;

    protected $type;
    protected $title;
    protected $body;
    protected $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $type, string $title, string $body, string $url = null)
    {
        $this->type = $type;
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->body,
            'url' => $this->url,
        ];
    }

    /**
     * Structure de la notification pour le stockage en base de données
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->body,
            'url' => $this->url,
        ];
    }
}
