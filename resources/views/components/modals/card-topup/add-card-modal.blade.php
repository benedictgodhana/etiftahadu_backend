<!-- Add Topup Modal -->
<div class="modal fade" id="addTopupModal" tabindex="-1" aria-labelledby="addTopupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTopupModalLabel">Add New Card Topup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('card-topups.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <!-- Card Select -->
                        <div class="col-md-6">
                            <label for="card_id" class="form-label">Select Card</label>
                            <select name="card_id" id="card_id" class="form-select" required>
                                <option value="">-- Select Card --</option>
                                @foreach($cards as $card)
                                    <option value="{{ $card->id }}">{{ $card->name }} ({{ $card->serial_number }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount Input -->
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Amount (Namibian Dollars)</label>
                            <div class="input-group">
                                <span class="input-group-text">N$</span>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Offer Select -->
                        <div class="col-md-6">
                            <label for="offer_id" class="form-label">Select Offer (Optional)</label>
                            <select name="offer_id" id="offer_id" class="form-select">
                                <option value="">-- No Offer --</option>
                                @foreach($offers as $offer)
                                    <option value="{{ $offer->id }}">{{ $offer->name}} - {{ $offer->duration }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Select -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Topup</button>
                </div>
            </form>
        </div>
    </div>
</div>
