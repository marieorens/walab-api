<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResultatDisponibleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $pdfPassword;
    public $clientName;

    /**
     * Create a new message instance.
     */
    public function __construct($commande, $pdfPassword, $clientName)
    {
        $this->commande = $commande;
        $this->pdfPassword = $pdfPassword;
        $this->clientName = $clientName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vos Résultats Médicaux sont Disponibles ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.resultat_disponible',
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
