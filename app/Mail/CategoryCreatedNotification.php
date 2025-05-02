<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CategoryCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function build()
    {
        return $this->subject('A New Category Has Been Created')
            ->markdown('emails.category-created')
            ->with([
                'category' => $this->category,
            ]);
    }
}
