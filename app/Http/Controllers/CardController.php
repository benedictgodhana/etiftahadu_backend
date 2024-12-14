<?php
// CardController (App\Http\Controllers\CardController.php)
namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class CardController extends Controller
{
    // Save NFC Card Details
    public function store(Request $request)
    {
        try {
            // Validate the input data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'tel' => 'required|string|max:15',
                'status' => 'required|string|in:Active,Inactive',
                'serial_number' => 'required|string|max:50|unique:nfc_cards',
            ]);

            // Get the authenticated user ID
            $user_id = auth()->id();

            // Create the NFC card
            $nfcCard = Card::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'tel' => $validated['tel'],
                'status' => $validated['status'],
                'serial_number' => $validated['serial_number'],
                'user_id' => 1, // use the authenticated user ID
            ]);

            // Create the initial transaction with an amount of 0 for the card
            $transaction = CardTransaction::create([
                'card_id' => $nfcCard->id, // associate with the created card
                'amount' => 0,              // initial amount set to 0

            ]);

            // Return success response with the NFC card and transaction details
            return response()->json([
                'message' => 'NFC card added successfully with initial transaction',
                'data' => [
                    'nfc_card' => $nfcCard,
                    'transaction' => $transaction,
                ],
            ], 201);

        } catch (\Exception $e) {
            // Handle errors and return failure response
            return response()->json([
                'message' => 'Failed to save NFC card data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function fetchCardData(Request $request)
    {
        // Validate the request input
        $request->validate([
            'serial_number' => 'required|string',
        ]);

        // Get the card serial number from the request
        $serialNumber = $request->input('serial_number');

        // Search for the card in the database using the correct field
        $nfcCard = Card::where('serial_number', $serialNumber)->first(); // Use 'serial_number', not 'tag'

        // Check if the card exists
        if ($nfcCard) {
            // Retrieve the most recent transaction for this card
            $lastTransaction = CardTransaction::where('card_id', $nfcCard->id)
                ->latest()
                ->first();

            // Check if a transaction exists
            $balance = $lastTransaction ? $lastTransaction->amount : 0; // Default to 0 if no transactions found

            // Return the card data and balance
            return response()->json([
                'success' => true,
                'message' => 'Card found',
                'data' => [
                    'serial_number' => $nfcCard->serial_number,
                    'name' => $nfcCard->name,
                    'email' => $nfcCard->email,
                    'tel' => $nfcCard->tel,
                    'card_id' => $nfcCard->id,
                    'balance' => $balance, // Include balance from the last transaction
                ]
            ]);
        }

        // If no card was found, return an error response
        return response()->json([
            'success' => false,
            'message' => 'Card not found',
        ], 404);
    }


    public function fetchActiveCards(Request $request)
    {
        try {
            // Fetch all active cards
            $activeCards = Card::where('status', 'Active')->get();

            // Count the number of active cards
            $activeCardsCount = $activeCards->count();

            // Return success response with the active cards and the count
            return response()->json([
                'success' => true,
                'message' => 'Fetched all active cards',
                'data' => [
                    'active_cards_count' => $activeCardsCount,
                    'active_cards' => $activeCards,
                ]
            ], 200);

        } catch (\Exception $e) {
            // Handle errors and return failure response
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active cards',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
