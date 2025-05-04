<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
{
    $isMerchant = $this->user->user_type === 'merchant';

    $subject = $isMerchant
        ? 'Welcome to Rentify - Account Under Review'
        : 'Welcome to Rentify!';

    return $this->subject($subject)
                ->view('emails.welcome')
                ->with([
                    'name' => $this->user->name,
                    'isMerchant' => $isMerchant,
                ]);
}

}
