<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $reason;

    public function __construct($product, $reason = null)
    {
        $this->product = $product;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your Product Has Been Deleted by Admin')
            ->markdown('emails.delete-product')
            ->with([
                'product' => $this->product,
                'reason' => $this->reason,
            ]);
    }
}
