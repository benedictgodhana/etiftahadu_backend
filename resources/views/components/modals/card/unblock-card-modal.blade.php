<!-- unblock-card-modal.blade.php -->
@foreach ($cards as $card)

<div class="modal fade" id="unblockCardModal{{ $card->id }}" tabindex="-1" aria-labelledby="unblockCardModalLabel{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unblockCardModalLabel{{ $card->id }}">Unblock Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cards.unblock', $card->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to unblock this card?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Unblock</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
