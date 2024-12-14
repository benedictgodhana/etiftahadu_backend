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

class CardTopupController extends Controller
{
    public function topUpCard(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'card_id' => 'required|exists:nfc_cards,id', // Ensure card exists
        'amount' => 'required|numeric|min:0.01', // Validate amount
    ]);

    try {
        // Start a database transaction to ensure atomic operations
        DB::beginTransaction();

        // Retrieve the card to be topped up
        $card = Card::findOrFail($validated['card_id']);

        // Auto-generate the transaction reference (UUID or timestamp)
        $transactionReference = Str::uuid(); // Alternatively, use `time()` or another method

        // Create a new top-up record in the card_top_ups table
        $topUp = CardTopUp::create([
            'card_id' => $validated['card_id'],
            'amount' => $validated['amount'],
            'transaction_reference' => $transactionReference,
            'status' => 'Active', // You can update this later based on your logic (e.g., 'Pending' during external verification)
            'user_id' => auth()->id(), // Use the authenticated user's ID
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

        // Send an email notification to the card's email
        Mail::to($card->email)->send(new CardTopUpNotification($topUp, $newBalance, $card->name));

        // Return a success response
        return response()->json([
            'message' => 'Card successfully topped up',
            'data' => $topUp,
            'new_balance' => $newBalance,
            'name' => $card->name,
        ], 201);

    } catch (\Exception $e) {
        // Rollback the transaction on exception
        DB::rollBack();

        // Return error response
        return response()->json([
            'message' => 'Failed to top up the card',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function index()
    {
        // Fetch all card top-ups with associated card and user data, with pagination (8 records per page)
        $topups = CardTopup::with(['card', 'user'])->paginate(8);

        // Return the data as JSON with pagination info
        return response()->json(['topups' => $topups]);
    }

    public function show($id)
    {
        // Fetch a single card top-up record by ID
        $topup = CardTopup::findOrFail($id);
        return response()->json(['data' => $topup]);
    }
}
