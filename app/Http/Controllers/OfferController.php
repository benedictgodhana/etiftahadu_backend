<?php
namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    /**
     * Display a listing of the offers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (request()->wantsJson()) {
            $offers = Offer::with('user:id,name')
                ->select('id', 'user_id', 'name', 'duration', 'expiry', 'created_at', 'updated_at', 'deleted_at', 'percentage')
                ->get();

            // Log the authenticated user and offer details
            Log::info('API offers request', [
                'requested_by_user_id' => auth()->id(),
                'offer_count' => $offers->count(),
                'creator_names' => $offers->pluck('user.name')->unique()->values(),
                'offers' => $offers->toArray()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $offers
            ]);
        }

        // Paginate for web view
        $offers = Offer::with('user:id,name')
            ->select('id', 'user_id', 'name', 'duration', 'expiry', 'created_at', 'updated_at', 'deleted_at', 'percentage')
            ->paginate(10);

        return view('offers.index', compact('offers'));
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
            'duration' => 'required|integer|min:1',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $expiry = now()->addDays($request->input('duration'));

        $request->merge([
            'user_id' => auth()->id(),
            'expiry' => $expiry,
        ]);

        $offer = Offer::create($request->all());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $offer], 201);
        }

        return back()->with('success', 'Offer created successfully!');
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
            'percentage' => 'nullable|numeric|min:0|max:100',
            'duration' => 'nullable|integer|min:1', // In case duration is updated
        ]);

        // Optional: recalculate expiry if duration is provided
        if ($request->filled('duration')) {
            $request->merge([
                'expiry' => now()->addDays($request->input('duration')),
            ]);
        }

        $offer->update($request->all());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $offer], 200);
        }

        return back()->with('success', 'Offer updated successfully!');
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

    if (request()->expectsJson()) {
        return response()->json(['success' => true, 'message' => 'Offer deleted successfully.'], 200);
    }

    return back()->with('success', 'Offer deleted successfully!');
}

}
