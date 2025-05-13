<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $body;

    public function __construct($user, $body)
    {
        $this->user = $user;
        $this->body = $body;
    }

    public function build()
    {
        return $this->view('emails.quotation_reminder')
                    ->with([
                        'body' => $this->body,
                        'user_name' => $this->user->user_name,
                    ])
                    ->subject('Quotation Not Completed');
    }
}
