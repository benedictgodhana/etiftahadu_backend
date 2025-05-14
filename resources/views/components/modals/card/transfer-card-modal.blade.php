<!-- transfer-card-modal.blade.php -->
@foreach ($cards as $card)

<div class="modal fade" id="transferCardModal{{ $card->id }}" tabindex="-1" aria-labelledby="transferCardModalLabel{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferCardModalLabel{{ $card->id }}">Transfer Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cards.transfer', $card->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Recipient</label>
                        <select class="form-control" id="recipient" name="recipient" required>
                            <option value="" disabled selected>Select Recipient</option>
                            @foreach ($cards as $recipientCard)
                                <option value="{{ $recipientCard->id }}">{{ $recipientCard->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Transaction Date</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
