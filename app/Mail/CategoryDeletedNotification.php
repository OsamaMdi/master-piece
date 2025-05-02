<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CategoryDeletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function build()
    {
        return $this->subject('Category Removed — Your Tools May Be Affected')
            ->markdown('emails.category-deleted')
            ->with([
                'category' => $this->category,
            ]);
    }
}
