<?php

namespace App\Jobs;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail; 
use Illuminate\Support\Facades\Log;

class SendNewsletter 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subject;
    protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscribers = NewsletterSubscriber::all();
        
        foreach ($subscribers as $subscriber) {
            try{
                Log::info('Info mail newletter send is: ', [
                    'email' => $subscriber->email,
                    'subject' => $this->subject,
                    'content' => $this->content,
                ]);
                Mail::to($subscriber->email)->send(new NewsletterMail($this->subject, $this->content));
            } catch (\Exception $e) {
                // Log l'erreur
                Log::error('Error sending newletter : ' . $e->getMessage(), [
                    'email' => $subscriber->email,
                    'subject' => $this->subject,
                    'content' => $this->content,
                ]);
            }
        }
    }
}
