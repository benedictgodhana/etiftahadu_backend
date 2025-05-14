<!-- block-card-modal.blade.php -->
@foreach ($cards as $card)

<div class="modal fade" id="blockCardModal{{ $card->id }}" tabindex="-1" aria-labelledby="blockCardModalLabel{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockCardModalLabel{{ $card->id }}">Block Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cards.block', $card->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to block this card?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Block</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
