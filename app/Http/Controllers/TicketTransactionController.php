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
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

    public function index(Request $request)
    {
        $query = TicketTransaction::with(['route', 'card']);

        // Filter by ticket number (if provided)
        if ($request->has('ticket_number') && $request->ticket_number) {
            $query->where('ticket_number', 'like', '%' . $request->ticket_number . '%');
        }

        // Filter by route (if provided)
        if ($request->has('route') && $request->route && $request->route != 'All') {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('from', 'like', '%' . $request->route . '%')
                  ->orWhere('to', 'like', '%' . $request->route . '%');
            });
        }

        // Filter by date range (if provided)
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->get();

        // Debugging: Check the transactions data
        Log::info('Transactions:', $transactions->toArray());

        return response()->json(['data' => $transactions]);
    }


    public function totalMonthlyTransactions()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Calculate the total transaction amount for the current month
        $totalMonthly = TicketTransaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                          ->sum('amount');

        return response()->json([
            'total_monthly_transactions' => $totalMonthly
        ]);
    }

    // Fetch total transactions for today
    public function totalTodaysTransactions()
    {
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        // Calculate the total transaction amount for today
        $totalToday = TicketTransaction::whereBetween('created_at', [$startOfDay, $endOfDay])
                                       ->sum('amount');

        return response()->json([
            'total_todays_transactions' => $totalToday
        ]);
    }
}
