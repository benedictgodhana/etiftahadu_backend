<?php
namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardTopUp;
use App\Models\CardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str; // For generating the transaction reference
use App\Mail\CardTopUpNotification;
use App\Models\Offer;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class CardTopupController extends Controller
{
    public function topUpCard(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'card_id' => 'required|exists:nfc_cards,id', // Ensure card exists
            'amount' => 'required|numeric|min:0.01', // Validate amount
            'offer_id' => 'nullable|exists:offers,id', // Ensure the offer exists if provided
        ]);

        try {
            // Start a database transaction to ensure atomic operations
            DB::beginTransaction();

            // Retrieve the card to be topped up
            $card = Card::findOrFail($validated['card_id']);

            // Retrieve the offer, if provided
            $offer = $validated['offer_id'] ? Offer::findOrFail($validated['offer_id']) : null;

            // Auto-generate the transaction reference (UUID or timestamp)
            $transactionReference = Str::uuid(); // Alternatively, use `time()` or another method

            // Calculate the expiry date based on the offer's duration (if applicable)
            $expiryDate = $offer ? now()->addDays($offer->duration) : null;

            // Create a new top-up record in the card_top_ups table
            $topUp = CardTopUp::create([
                'card_id' => $validated['card_id'],
                'amount' => $validated['amount'],
                'transaction_reference' => $transactionReference,
                'status' => 'Active', // You can update this later based on your logic (e.g., 'Pending' during external verification)
                'user_id' => auth()->id(), // Use the authenticated user's ID
                'offer_id' => $validated['offer_id'], // Associate with the offer
                'expiry_date' => $expiryDate, // Set the expiry date
            ]);

            // Retrieve the last transaction for this card
            $lastTransaction = CardTransaction::where('card_id', $validated['card_id'])
                ->latest()
                ->first();

            // Calculate the new balance
            $newBalance = $lastTransaction ? $lastTransaction->amount + $validated['amount'] : $validated['amount'];

            // If a previous transaction exists, update it; otherwise, create a new one
            if ($lastTransaction) {
                $lastTransaction->update([
                    'amount' => $newBalance,
                ]);
            } else {
                CardTransaction::create([
                    'card_id' => $validated['card_id'],
                    'amount' => $newBalance,
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Set the DomPDF options for fonts
            $pdf = FacadePdf::loadView('emails.card_top_up', [
                'topUp' => $topUp,
                'newBalance' => $newBalance,
                'name' => $card->name,
                'offer' => $offer ? $offer->expiry : null, // Include offer details in the receipt
            ]);

            // Access DomPDF options and set custom font directories
            $options = $pdf->getDomPDF()->getOptions();
            $options->set('fontDir', base_path('public/fonts/'));
            $options->set('fontCache', base_path('public/fonts/'));
            $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser for better CSS handling
            $options->set('isPhpEnabled', true); // Enable PHP if needed for advanced operations
            $options->set('defaultFont', 'sans-serif'); // Use your font, e.g., 'Roboto', 'Arial'

            // Reapply options to the PDF instance
            $pdf->getDomPDF()->setOptions($options);

            // Send email notification with the PDF receipt as an attachment
            Mail::to($card->email)->send(new CardTopUpNotification($topUp, $newBalance, $card->name, $pdf));

            // Check if it's an API request or web request and respond accordingly
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Card successfully topped up',
                    'data' => $topUp,
                    'new_balance' => $newBalance,
                    'name' => $card->name,
                ], 201);
            } else {
                // Redirect back with success message for web requests
                return redirect()->back()->with('success', 'Card successfully topped up');
            }

        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();

            // Check if it's an API request or web request and respond accordingly
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to top up the card',
                    'error' => $e->getMessage(),
                ], 500);
            } else {
                // Redirect back with error message for web requests
                return redirect()->back()->with('error', 'Failed to top up the card: ' . $e->getMessage());
            }
        }
    }



    public function index()
    {
        // Fetch all card top-ups with associated card, user, and offer data, with pagination (8 records per page)
        $topups = CardTopup::with(['card', 'user', 'offer'])->paginate(8);

        // Fetch all cards and offers
        $cards = Card::all();
        $offers = Offer::all();

        // Return the view with all necessary data
        return view('card-topups.index', compact('topups', 'cards', 'offers'));
    }


    public function show($id)
    {
        // Fetch a single card top-up record by ID
        $topup = CardTopup::findOrFail($id);
        return response()->json(['data' => $topup]);
    }
}
