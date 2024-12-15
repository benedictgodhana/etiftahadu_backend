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
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

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
            return response([
                'error' => 'Insufficient balance.',
                'message' => 'Please top-up and try again.'
            ], 400);
        }

        // Check if there's an existing CardTransaction record for this card
        $existingTransaction = CardTransaction::where('card_id', $validated['card_id'])->latest()->first();

        if (!$existingTransaction) {
            return response([
                'error' => 'No previous transaction found for this card.',
                'message' => 'Please ensure you have a valid transaction record.'
            ], 400);
        }

        // Update the existing transaction to deduct the fare
        $existingTransaction->amount -= $validated['amount']; // Deduct the amount from the existing transaction
        $existingTransaction->save(); // Save the updated transaction

        // Create the transaction record in the TicketTransaction table
        $transaction = TicketTransaction::create($validated);

        // Get the route details
        $route = Route::find($validated['route_id']);

        // Send email to the card holder with the receipt attached
        $this->sendTicketEmail($card, $route, $validated['amount']);

        return response([
            'success' => true,
            'message' => 'Ticket purchased successfully!',
            'ticketNumber' => $transaction->ticket_number,
            'route' => $route,
            'newBalance' => $existingTransaction->amount // Optional: Return updated card balance
        ], 201);
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


    public function printReceipt($ticketId)
    {
        try {
            // Retrieve the transaction details
            $transaction = TicketTransaction::with(['route', 'card'])->findOrFail($ticketId);

            // ETR printer connection setup (Adjust to your system's connection details)
            $connector = new NetworkPrintConnector("192.168.0.100", 9100);
            // Create printer instance
            $printer = new Printer($connector);

            // Print logo (if exists)
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            try {
                $logoPath = public_path('images/logo.png'); // Update with your logo path
                if (file_exists($logoPath)) {
                    $logo = EscposImage::load($logoPath, false);
                    $printer->graphics($logo);
                }
            } catch (\Exception $e) {
                // Handle image load failure
                $printer->text("NAMIBIA COMMUTER HAULAGE\n");
            }

            $printer->text("-----------------------------\n");

            // Print receipt details
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Ticket Number: " . $transaction->ticket_number . "\n");
            $printer->text("Route: " . $transaction->route->from . " to " . $transaction->route->to . "\n");
            $printer->text("Amount: KES " . number_format($transaction->amount, 2) . "\n");
            $printer->text("Date: " . $transaction->created_at->format('Y-m-d H:i:s') . "\n");
            $printer->text("-----------------------------\n");

            // Print QR code
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->qrCode($transaction->ticket_number);

            // Closing message
            $printer->text("\nThank you for using our service!\n");

            // Finish printing
            $printer->cut();
            $printer->close();

            return response()->json(['message' => 'Receipt printed successfully.'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to print receipt.', 'details' => $e->getMessage()], 500);
        }
    }

}
