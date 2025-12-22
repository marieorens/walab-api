<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Withdrawal $withdrawal;
    protected string $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(Withdrawal $withdrawal, string $action)
    {
        $this->withdrawal = $withdrawal;
        $this->action = $action; // 'approved', 'rejected', 'cancelled'
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
        $amount = number_format($this->withdrawal->montant, 0, ',', ' ');
        $periode = $this->withdrawal->periode;

        if ($this->action === 'approved') {
            return (new MailMessage)
                ->subject('Votre retrait a été approuvé - Walab')
                ->greeting('Bonjour ' . $notifiable->firstname . ',')
                ->line("Bonne nouvelle ! Votre demande de retrait a été approuvée.")
                ->line("**Montant:** {$amount} FCFA")
                ->line("**Période:** {$periode}")
                ->line("Le virement sera effectué dans les prochains jours ouvrables.")
                ->line($this->withdrawal->notes ? "**Note:** {$this->withdrawal->notes}" : '')
                ->action('Voir mon portefeuille', url('/laboratoire/wallet'))
                ->line('Merci de votre confiance !');
        }

        if ($this->action === 'rejected') {
            return (new MailMessage)
                ->subject('Votre retrait a été rejeté - Walab')
                ->greeting('Bonjour ' . $notifiable->firstname . ',')
                ->line("Nous avons le regret de vous informer que votre demande de retrait a été rejetée.")
                ->line("**Montant:** {$amount} FCFA")
                ->line("**Période:** {$periode}")
                ->line("**Raison:** " . ($this->withdrawal->notes ?? 'Non spécifiée'))
                ->line("Si vous avez des questions, veuillez contacter notre support.")
                ->action('Voir mon portefeuille', url('/laboratoire/wallet'))
                ->line('L\'équipe Walab');
        }

        // cancelled
        return (new MailMessage)
            ->subject('Votre retrait a été annulé - Walab')
            ->greeting('Bonjour ' . $notifiable->firstname . ',')
            ->line("Votre demande de retrait a été annulée.")
            ->line("**Montant:** {$amount} FCFA")
            ->line("**Période:** {$periode}")
            ->action('Voir mon portefeuille', url('/laboratoire/wallet'))
            ->line('L\'équipe Walab');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'withdrawal_' . $this->action,
            'withdrawal_id' => $this->withdrawal->id,
            'montant' => $this->withdrawal->montant,
            'periode' => $this->withdrawal->periode,
            'action' => $this->action,
            'notes' => $this->withdrawal->notes,
            'message' => $this->getNotificationMessage(),
        ];
    }

    protected function getNotificationMessage(): string
    {
        $amount = number_format($this->withdrawal->montant, 0, ',', ' ');

        return match($this->action) {
            'approved' => "Votre retrait de {$amount} FCFA a été approuvé",
            'rejected' => "Votre retrait de {$amount} FCFA a été rejeté",
            'cancelled' => "Votre retrait de {$amount} FCFA a été annulé",
            default => "Votre retrait de {$amount} FCFA a été traité",
        };
    }
}
