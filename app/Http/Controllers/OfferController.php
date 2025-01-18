<?php
namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the offers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $offers = Offer::with('user:id,name') // Use `with` to include the user's name
            ->select('id', 'user_id', 'name', 'duration', 'expiry', 'created_at', 'updated_at', 'deleted_at')
            ->get();

        return response()->json($offers);
    }



    /**
     * Show the form for creating a new offer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json(['message' => 'Display offer creation form']);
    }

    /**
     * Store a newly created offer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1', // Ensure duration is provided and is a positive integer
        ]);

        // Calculate expiry date based on the current date and duration
        $expiry = now()->addDays($request->input('duration'));

        // Merge additional fields into the request data
        $request->merge([
            'user_id' => auth()->id(), // Authenticated user's ID
            'expiry' => $expiry,
        ]);

        // Create the offer
        $offer = Offer::create($request->all());

        return response()->json(['success' => true, 'data' => $offer], 201);
    }

    /**
     * Display the specified offer.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Offer $offer)
    {
        return response()->json(['success' => true, 'data' => $offer], 200);
    }

    /**
     * Show the form for editing the specified offer.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Offer $offer)
    {
        return response()->json(['message' => 'Display offer edit form']);
    }

    /**
     * Update the specified offer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'expiry' => 'required|date',
        ]);

        $offer->update($request->all());

        return response()->json(['success' => true, 'data' => $offer], 200);
    }

    /**
     * Remove the specified offer from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return response()->json(['success' => true, 'message' => 'Offer deleted successfully.'], 200);
    }
}
