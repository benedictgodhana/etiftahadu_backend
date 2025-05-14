<!-- Add Offer Modal -->
<div class="modal fade" id="addOfferModal" tabindex="-1" aria-labelledby="addOfferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOfferModalLabel">Add New Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('offers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="offerName" class="form-label">Offer Name</label>
                        <input type="text" class="form-control" id="offerName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (days)</label>
                        <input type="number" class="form-control" id="duration" name="duration" required min="1">
                        <div class="form-text">Expiry will be auto-calculated based on today's date + duration.</div>
                    </div>
                    <div class="mb-3">
                        <label for="percentage" class="form-label">Percentage (%)</label>
                        <input type="number" class="form-control" id="percentage" name="percentage" required min="0" max="100" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>
