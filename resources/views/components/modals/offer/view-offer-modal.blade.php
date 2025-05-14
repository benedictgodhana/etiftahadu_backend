<!-- View Offer Modal -->
@foreach ($offers as $offer)
<div class="modal fade" id="viewOfferModal{{ $offer->id }}" tabindex="-1" aria-labelledby="viewOfferModalLabel{{ $offer->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOfferModalLabel{{ $offer->id }}">View Offer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Offer Name:</strong> {{ $offer->name }}</p>
                <p><strong>Duration:</strong> {{ $offer->duration }} days</p>
                <p><strong>Percentage:</strong> {{ $offer->percentage }}%</p>
                <p><strong>Expiry Date:</strong>
                    {{ \Carbon\Carbon::parse($offer->expiry)->format('Y-m-d') }}
                </p>
                <p><strong>Created At:</strong>
                    {{ $offer->created_at ? $offer->created_at->format('Y-m-d') : 'N/A' }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
