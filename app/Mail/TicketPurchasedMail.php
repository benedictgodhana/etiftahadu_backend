<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TicketPurchasedMail extends Mailable
{
    public $emailData;

    // Constructor accepts the email data array
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    // Build the email and pass the data to the view
    public function build()
    {
        return $this->view('emails.ticket_receipt') // Ensure you're using the correct view path
                    ->with([
                        'name' => $this->emailData['name'],
                        'from' => $this->emailData['from'],
                        'to' => $this->emailData['to'],
                        'amount' => $this->emailData['amount'],
                        'new_balance' => $this->emailData['new_balance'],
                    ])
                    ->subject('Ticket Purchased - Namibia Contract Haulage');
    }
}
