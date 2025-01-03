<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade as PDF;

class CardTopUpNotification extends Mailable
{
    use SerializesModels;

    public $topUp;
    public $newBalance;
    public $name;
    public $pdf; // PDF data to be attached

    // Pass data to the mailable
    public function __construct($topUp, $newBalance, $name, $pdf)
    {
        $this->topUp = $topUp;
        $this->newBalance = $newBalance;
        $this->name = $name;
        $this->pdf = $pdf; // Store the PDF file or its data for attachment
    }

    // Build the email message
    public function build()
    {
        return $this->subject('Card Top-Up Notification')
                    ->view('emails.card_top_up') // Blade view for email content
                    ->attachData($this->pdf->output(), 'receipt.pdf', [ // Attach the PDF receipt
                        'mime' => 'application/pdf',
                    ]);
    }
}
