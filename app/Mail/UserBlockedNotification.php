<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserBlockedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;
    public $duration;

    public function __construct($user, $reason, $duration)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->duration = $duration;
    }

    public function build()
    {
        return $this->subject('Account Blocked Notification')
            ->markdown('emails.blocked')
            ->with([
                'user' => $this->user,
                'reason' => $this->reason,
                'duration' => $this->duration,
            ]);
    }
}
