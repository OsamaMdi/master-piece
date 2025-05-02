<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $product;
    public $reservation;

    public function __construct($user, $product, $reservation)
    {
        $this->user = $user;
        $this->product = $product;
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Your Reservation Has Been Confirmed!')
                    ->view('emails.booking_confirmed')
                    ->with([
                        'user' => $this->user,
                        'product' => $this->product,
                        'reservation' => $this->reservation,
                    ]);
    }
}
