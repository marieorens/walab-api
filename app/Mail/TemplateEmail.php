<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TemplateEmail extends Mailable
{
    protected $body;

    public function __construct($body)
    {
        $this->body = $body;
    }

    public function build()
    {
        return $this->view('emails.template')
                ->with(['body' => $this->body]);
     }
}