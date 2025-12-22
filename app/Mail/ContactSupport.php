<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class ContactSupport extends Mailable
{
    use Queueable;
    use SerializesModels, MailerSendTrait;

    public function __construct(public $body) {}
    /**
     * Configure the email message
     */
    public function envelope()
    {
        $email = config('mail.from.address');
        // $email = "contact@support.com";
        return new Envelope(
            from: new Address($email, 'Walab'),
            subject: 'Request Support',
        );
    }
    /**
     * Generate the body
     */
    public function content()
    {
        // return new Content(
        //     markdown: 'emails.template',
        // );
        return $this->view('emails.template')
                ->with(['body' => $this->body]);
    }
    /**
     * Configure the email attachements
     */
    public function attachments()
    {
        return [
            // 
        ];
    }
}
