<?php
namespace App\Http\Controllers;

use App\Models\TicketTransaction;
use App\Models\Card;
use App\Models\Route;
use App\Models\CardTransaction;  // Import CardTransaction model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchasedMail;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class TicketTransactionController extends Controller
{
    // Store a new transaction
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'card_id' => 'required|exists:nfc_cards,id',  // Ensure 'nfc_cards' table is used
            'amount' => 'nullable|numeric',
        ]);

        // Get the card and check if balance is sufficient
        $card = Card::find($validated['card_id']);  // Corrected to use 'card_id'

        // Calculate the current balance from the CardTransaction table
        $currentBalance = CardTransaction::where('card_id', $card->id)->sum('amount');

        // Ensure the card has enough balance
        if ($currentBalance < $validated['amount']) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        // Check if there's an existing CardTransaction record for this card
        $existingTransaction = CardTransaction::where('card_id', $validated['card_id'])->latest()->first();

        if ($existingTransaction) {
            // Update the existing transaction to deduct the fare
            $existingTransaction->amount -= $validated['amount']; // Deduct the amount from the existing transaction
            $existingTransaction->save(); // Save the updated transaction
        } else {
            // If no existing transaction, don't create a new one (as per your request)
            // You can choose to handle this case differently or throw an error if needed.
            return response()->json(['error' => 'No previous transaction found for this card.'], 400);
        }

        // Create the transaction record in the TicketTransaction table
        $transaction = TicketTransaction::create($validated);

        // Get the route details
        $route = Route::find($validated['route_id']);

        // Send email to the card holder with the receipt attached
        $this->sendTicketEmail($card, $route, $validated['amount']);

        return response()->json(['success' => true, 'data' => $transaction], 201);
    }

    // Send email after ticket purchase
    protected function sendTicketEmail($card, $route, $amount)
    {
        // Calculate the current balance from the CardTransaction table
        $currentBalance = CardTransaction::where('card_id', $card->id)->sum('amount');

        // Calculate the new balance after the transaction (deduct the fare)
        $newBalance = $currentBalance ;

        // Prepare email data
        $emailData = [
            'name' => $card->name,
            'from' => $route->from,  // Include route name or any other detail
            'to' => $route->to,  // Include route name or any other detail
            'amount' => $amount,
            'new_balance' => $newBalance,  // Pass the new balance after deduction
        ];

        // Generate the PDF receipt
        $pdf = FacadePdf::loadView('emails.ticket_receipt', $emailData);

        // Prepare the email
        $email = new TicketPurchasedMail($emailData);

        // Attach the PDF receipt to the email
        $email->attachData($pdf->output(), 'receipt.pdf', [
            'mime' => 'application/pdf',
        ]);

        // Send the email with the attached PDF
        Mail::to($card->email)->send($email);
    }

    // Fetch all transactions
    public function index()
    {
        $transactions = TicketTransaction::with(['route', 'card'])->get();

        return response()->json(['data' => $transactions]);
    }
}
