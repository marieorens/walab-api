<?php

namespace App\Mail;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCommandeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $client;
    public $laboratoire;
    public $items;

    /**
     * Create a new message instance.
     */
    public function __construct(Commande $commande, $laboratoire = null)
    {
        $this->commande = $commande;
        $this->client = $commande->client;
        $this->laboratoire = $laboratoire;
        
        // Récupérer les items de la commande (examens ou bilans)
        $this->items = $this->getCommandeItems($commande);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle Commande - ' . $this->commande->code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-commande',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Récupérer les détails des items de la commande
     */
    private function getCommandeItems($commande)
    {
        $items = [];
        
        if ($commande->examen) {
            $items[] = [
                'type' => 'Examen',
                'name' => $commande->examen->label,
                'price' => $commande->examen->price,
            ];
        }
        
        if ($commande->type_bilan) {
            $items[] = [
                'type' => 'Bilan',
                'name' => $commande->type_bilan->label,
                'price' => $commande->type_bilan->price,
            ];
        }
        
        return $items;
    }
}
