<?php

namespace App\Services;

use App\Mail\ContactSupport;
use App\Mail\TemplateEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Illuminate\Support\Facades\Log;


class SendMailService
{
    public function sendMailWithAttachment(string $id, string $body, string $pdfPath, string $pdfName)
    {
        $email = User::Where('id', $id)->first()->email;
        try{
            Mail::to($email)
                ->send(new \App\Mail\ResultatWithAttachmentMail($body, $pdfPath, $pdfName));
        }
        catch (Throwable $e) {
            Log::error('Error sending mail with attachment : ' . $e->getMessage(), [
                'email' => $email,
            ]);
        }
    }


    public function __construct()
    {

    }

    public function sendMail(string $id, string $body)
    {
        $email = User::Where('id', $id)->first()->email;
        try{
            Mail::to($email)
            ->send(new TemplateEmail($body));
        }
        catch (Throwable $e) {
            Log::error('Error sending mail : ' . $e->getMessage(), [
                'email' => $email,
            ]);
        }
            
    }

    public function sendMailSupport(string $body)
    {
        $email = config('mail.from.address');

        try{
            Mail::to($email)
            ->send(new ContactSupport($body));
        }
        catch (Throwable $e) {
            Log::error('Error sending mail : ' . $e->getMessage(), [
                'email' => $email,
            ]);
        }
            
    }
}