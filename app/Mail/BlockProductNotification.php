<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BlockProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $reason;
    public $duration;

    public function __construct($product, $reason, $duration)
    {
        $this->product = $product;
        $this->reason = $reason;
        $this->duration = $duration;
    }

    public function build()
    {
        return $this->subject('Your Product Has Been Blocked')
            ->markdown('emails.block-product')
            ->with([
                'product' => $this->product,
                'reason' => $this->reason,
                'duration' => $this->duration,
            ]);
    }
}
