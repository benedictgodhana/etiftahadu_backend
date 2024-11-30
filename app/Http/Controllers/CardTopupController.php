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
            $transactionReference = Str::uuid();  // Alternatively, you could use `time()` or another method

            // Create a new top-up record in the card_top_ups table
            $topUp = CardTopUp::create([
                'card_id' => $validated['card_id'],
                'amount' => $validated['amount'],
                'transaction_reference' => $transactionReference,
                'status' => 'Active', // You can update this later based on your logic (e.g., 'Pending' during external verification)
                'user_id' => 1, // Set user_id to 1
            ]);

            // Retrieve the last transaction for this card
            $lastTransaction = CardTransaction::where('card_id', $validated['card_id'])
                ->latest()
                ->first();

            // If no previous transaction exists, start the balance with the top-up amount
            $newBalance = $lastTransaction ? $lastTransaction->amount + $validated['amount'] : $validated['amount'];

            // If a previous transaction exists, update the existing transaction record
            if ($lastTransaction) {
                $lastTransaction->update([
                    'amount' => $newBalance, // Update the balance with the new amount
                ]);
            } else {
                // If no previous transaction, create a new one with the top-up amount
                CardTransaction::create([
                    'card_id' => $validated['card_id'],
                    'amount' => $newBalance, // Store the initial balance after the top-up
                ]);
            }

            // Commit the transaction, ensuring both operations are saved atomically
            DB::commit();

            // Send an email notification to the card's email
            Mail::to($card->email)->send(new CardTopUpNotification($topUp, $newBalance, $card->name));

            // Return a success response with the top-up details and new balance
            return response()->json([
                'message' => 'Card successfully topped up',
                'data' => $topUp,
                'new_balance' => $newBalance, // Return the updated balance
                'name' => $card->name, // Return the updated balance

            ], 201);

        } catch (\Exception $e) {
            // If any exception occurs, rollback the transaction to maintain data integrity
            DB::rollBack();

            // Return error response with the exception message
            return response()->json([
                'message' => 'Failed to top up the card',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
