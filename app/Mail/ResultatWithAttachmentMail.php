<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResultatWithAttachmentMail extends Mailable
{
    protected $body;
    protected $pdfPath;
    protected $pdfName;

    public function __construct($body, $pdfPath, $pdfName)
    {
        $this->body = $body;
        $this->pdfPath = $pdfPath;
        $this->pdfName = $pdfName;
    }

    public function build()
    {
        return $this->view('emails.template')
            ->with(['body' => $this->body])
            ->attach($this->pdfPath, [
                'as' => $this->pdfName,
                'mime' => 'application/pdf',
            ]);
    }
}
