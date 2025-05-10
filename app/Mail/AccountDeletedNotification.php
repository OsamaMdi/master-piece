<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountDeletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $contactUrl;

    public function __construct($user)
    {
        $this->user = $user;

        $baseUrl = rtrim(config('app.email_url'), '/');
        $this->contactUrl = $baseUrl . route('contact', [], false);
    }

    public function build()
    {
        return $this->subject('Your Account Has Been Deleted')
            ->markdown('emails.account_deleted')
            ->with([
                'name' => $this->user->name,
                'contactUrl' => $this->contactUrl,
            ]);
    }
}
