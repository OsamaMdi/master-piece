<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationCancelledWithSuggestions extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $product;
    public $suggestedProducts;

    public function __construct($reservation, $product, $suggestedProducts)
    {
        $this->reservation = $reservation;
        $this->product = $product;
        $this->suggestedProducts = $suggestedProducts;
    }

    public function build()
    {
        return $this->subject('Your Reservation has been Cancelled')
                    ->markdown('emails.reservations.cancelledDate');
    }
}

