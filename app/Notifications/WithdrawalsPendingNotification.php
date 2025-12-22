<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalsPendingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected int $count;
    protected string $periode;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $count, string $periode)
    {
        $this->count = $count;
        $this->periode = $periode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🏦 {$this->count} retrait(s) en attente de traitement - Walab")
            ->greeting('Bonjour ' . $notifiable->firstname . ',')
            ->line("Les retraits mensuels pour la période **{$this->periode}** ont été générés.")
            ->line("**{$this->count} retrait(s)** sont en attente de traitement.")
            ->line('Veuillez vous connecter à l\'administration pour les traiter.')
            ->action('Gérer les retraits', url('/wallets/withdrawals'))
            ->line('Merci de traiter ces demandes dans les meilleurs délais.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'withdrawals_pending',
            'count' => $this->count,
            'periode' => $this->periode,
            'message' => "{$this->count} retrait(s) en attente pour la période {$this->periode}",
        ];
    }
}
