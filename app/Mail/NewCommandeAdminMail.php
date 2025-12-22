<?php

namespace App\Mail;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCommandeAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $client;
    public $laboratoire;

    /**
     * Create a new message instance.
     */
    public function __construct(Commande $commande, $laboratoire = null)
    {
        $this->commande = $commande;
        $this->client = $commande->client;
        $this->laboratoire = $laboratoire;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Admin] Nouvelle Commande - ' . $this->commande->code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-commande-admin',
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
}
