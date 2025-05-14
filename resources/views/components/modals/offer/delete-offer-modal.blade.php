<!-- Delete Offer Modal -->
@foreach ($offers as $offer)
<div class="modal fade" id="deleteOfferModal{{ $offer->id }}" tabindex="-1" aria-labelledby="deleteOfferModalLabel{{ $offer->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteOfferModalLabel{{ $offer->id }}">Delete Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('offers.destroy', $offer->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete the offer "{{ $offer->name }}"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
