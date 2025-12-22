<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class SendPushNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;
    public $actionUrl;
    public $actionText;

    public function __construct($title, $body, $actionUrl = '/', $actionText = 'Voir')
    {
        $this->title = $title;
        $this->body = $body;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class, 'database'];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->action($this->actionText, 'notification_action')
            ->data(['url' => $this->actionUrl]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->actionUrl,
            'created_at' => now()
        ];
    }
}
