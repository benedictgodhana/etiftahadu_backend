<!-- Edit Offer Modal -->
@foreach ($offers as $offer)
<div class="modal fade" id="editOfferModal{{ $offer->id }}" tabindex="-1" aria-labelledby="editOfferModalLabel{{ $offer->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOfferModalLabel{{ $offer->id }}">Edit Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('offers.update', $offer->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="offerName{{ $offer->id }}" class="form-label">Offer Name</label>
                        <input type="text" class="form-control" id="offerName{{ $offer->id }}" name="name" value="{{ $offer->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="duration{{ $offer->id }}" class="form-label">Duration (days)</label>
                        <input type="number" class="form-control" id="duration{{ $offer->id }}" name="duration" value="{{ $offer->duration }}" required min="1">
                        <div class="form-text">Expiry will be recalculated automatically.</div>
                    </div>
                    <div class="mb-3">
                        <label for="percentage{{ $offer->id }}" class="form-label">Percentage (%)</label>
                        <input type="number" class="form-control" id="percentage{{ $offer->id }}" name="percentage" value="{{ $offer->percentage }}" required min="0" max="100" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
