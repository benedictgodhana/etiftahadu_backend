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
                'user_id' => $user_id ?? 1, // use the authenticated user ID or fallback to 1
            ]);

            // Create the initial transaction with an amount of 0 for the card
            $transaction = CardTransaction::create([
                'card_id' => $nfcCard->id, // associate with the created card
                'amount' => 0, // initial amount set to 0
            ]);

            // Check if request is AJAX/API or expects JSON
            if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
                // Return JSON response for API/AJAX requests
                return response()->json([
                    'message' => 'NFC card added successfully with initial transaction',
                    'data' => [
                        'nfc_card' => $nfcCard,
                        'transaction' => $transaction,
                    ],
                ], 201);
            } else {
                // For web form submissions, redirect with flash message
                return redirect()->route('cards.index')
                    ->with('success', 'NFC card added successfully with initial transaction');
            }
        } catch (\Exception $e) {
            // Handle errors differently based on request type
            if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Failed to save NFC card data',
                    'error' => $e->getMessage(),
                ], 500);
            } else {
                return redirect()->back()
                    ->withErrors(['error' => 'Failed to save NFC card: ' . $e->getMessage()])
                    ->withInput();
            }
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
            $activeCards = Card::all();

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

    public function index()
    {
        // Fetch all cards
        $cards = Card::paginate(10);

        // Return a Blade view with the cards data
        return view('cards.index', compact('cards'));
    }


    public function update(Request $request, $id)
    {
        try {
            // Normalize status input (make first letter uppercase)
            if ($request->has('status')) {
                $request->merge([
                    'status' => ucfirst(strtolower($request->input('status')))
                ]);
            }

            // Validate the input data
            $validated = $request->validate([
                'name' => 'sometimes|nullable|string|max:255',
                'email' => 'sometimes|nullable|email|max:255',
                'tel' => 'sometimes|nullable|string|max:15',
                'status' => 'sometimes|nullable|string|in:Active,Inactive',
                'serial_number' => 'sometimes|nullable|string|max:50|unique:nfc_cards,serial_number,' . $id,
                'transaction_amount' => 'sometimes|nullable|numeric|min:0',
            ]);

            // Find the NFC card by ID
            $nfcCard = Card::findOrFail($id);

            // Update only provided fields
            $nfcCard->update(array_filter([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'tel' => $validated['tel'] ?? null,
                'status' => $validated['status'] ?? null,
                'serial_number' => $validated['serial_number'] ?? null,
            ]));

            // Optionally update or create transaction amount
            $transaction = null;
            if (isset($validated['transaction_amount'])) {
                $transaction = CardTransaction::where('card_id', $nfcCard->id)->first();

                if ($transaction) {
                    $transaction->update([
                        'amount' => $validated['transaction_amount'],
                    ]);
                } else {
                    $transaction = CardTransaction::create([
                        'card_id' => $nfcCard->id,
                        'amount' => $validated['transaction_amount'],
                    ]);
                }
            }

            // Check if request is AJAX/API or expects JSON
            if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'NFC card updated successfully',
                    'data' => [
                        'nfc_card' => $nfcCard,
                        'transaction' => $transaction,
                    ],
                ]);
            } else {
                return redirect()->route('cards.index')
                    ->with('success', 'NFC card updated successfully');
            }

        } catch (\Exception $e) {
            // Handle errors based on request type
            if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Failed to update NFC card data',
                    'error' => $e->getMessage(),
                ], 500);
            } else {
                return redirect()->back()
                    ->withErrors(['error' => 'Failed to update NFC card: ' . $e->getMessage()])
                    ->withInput();
            }
        }
    }

    public function blockCard($cardId)
    {
        try {
            $card = Card::findOrFail($cardId);

            // Update the card status to 'Blocked'
            $card->status = 'Blocked';
            $card->save();

            // Check if the request is for a web or API response
            if (request()->expectsJson()) {
                // For mobile (API) return JSON response
                return response()->json([
                    'message' => 'Card blocked successfully',
                    'data' => $card,
                ], 200);
            }

            // For web (browser) return a redirect back
            return back()->with('success', 'Card blocked successfully.');

        } catch (\Exception $e) {
            // Check if the request is for a web or API response
            if (request()->expectsJson()) {
                // For mobile (API) return JSON error response
                return response()->json([
                    'message' => 'Failed to block card',
                    'error' => $e->getMessage(),
                ], 500);
            }

            // For web (browser) return a redirect back with error message
            return back()->with('error', 'Failed to block card.');
        }
    }

    public function unblockCard($id)
    {
        try {
            $card = Card::findOrFail($id);

            // Unblock the card (update the status or other field as required)
            $card->status = 'Active'; // Example: Change status to 'Active' (or another status you use for active cards)
            $card->save();

            // Check if the request is for a web or API response
            if (request()->expectsJson()) {
                // For mobile (API) return JSON response
                return response()->json([
                    'message' => 'Card successfully unblocked.',
                    'card' => $card,
                ]);
            }

            // For web (browser) return a redirect back
            return back()->with('success', 'Card successfully unblocked.');

        } catch (\Exception $e) {
            // Check if the request is for a web or API response
            if (request()->expectsJson()) {
                // For mobile (API) return JSON error response
                return response()->json([
                    'message' => 'Failed to unblock card.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            // For web (browser) return a redirect back with error message
            return back()->with('error', 'Failed to unblock card.');
        }
    }


    public function transferCard(Request $request, $cardId)
{
    try {
        // Validate the request data
        $validated = $request->validate([
            'receiver_card_id' => 'required|exists:nfc_cards,id', // Ensure receiver card exists
            'amount' => 'required|numeric|min:0.01', // Validate transfer amount
        ]);

        // Retrieve the card to be transferred
        $card = Card::findOrFail($cardId);
        $receiverCard = Card::findOrFail($validated['receiver_card_id']);

        // Ensure the sender has sufficient funds
        if ($card->balance < $validated['amount']) {
            return response()->json([
                'message' => 'Insufficient funds',
            ], 400);
        }

        // Deduct the amount from the sender's card and add to the receiver's card
        $card->balance -= $validated['amount'];
        $receiverCard->balance += $validated['amount'];

        // Save the updated card balances
        $card->save();
        $receiverCard->save();

        // Create a new card transaction (optional)
        CardTransaction::create([
            'card_id' => $card->id,
            'amount' => -$validated['amount'], // Deducted amount from sender
            'transaction_type' => 'Transfer', // Transaction type for transfer
        ]);
        CardTransaction::create([
            'card_id' => $receiverCard->id,
            'amount' => $validated['amount'], // Added amount to receiver
            'transaction_type' => 'Transfer', // Transaction type for transfer
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Card transfer successful',
            'sender_balance' => $card->balance,
            'receiver_balance' => $receiverCard->balance,
        ], 200);

    } catch (\Exception $e) {
        // Handle exception and return an error response
        return response()->json([
            'message' => 'Failed to transfer card',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
