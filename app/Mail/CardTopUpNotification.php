<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardTopUpNotification extends Mailable
{
    use SerializesModels;

    public $topUp;
    public $newBalance;
    public $name;


    // Pass data to the mailable
    public function __construct($topUp, $newBalance, $name)
    {
        $this->topUp = $topUp;
        $this->newBalance = $newBalance;
        $this->name = $name;

    }

    // Build the email message
    public function build()
    {
        return $this->subject('Card Top-Up Notification')
                    ->view('emails.card_top_up'); // Create a Blade view for the email content
    }
}
