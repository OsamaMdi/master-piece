<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class SendWelcomeEmail
{
    public function handle(Registered $event)
    {

        logger('🚀 Listener triggered for user: ' . $event->user->email);

        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
        \logger('📬 Welcome email sent to: ' . $event->user->email);
    }
}

