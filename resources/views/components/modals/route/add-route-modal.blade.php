<!-- Add Route Modal -->
<div class="modal fade" id="addRouteModal" tabindex="-1" aria-labelledby="addRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRouteModalLabel">Add New Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('routes.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="from" class="form-label">From</label>
                        <input type="text" class="form-control" id="from" name="from" required>
                    </div>
                    <div class="mb-3">
                        <label for="to" class="form-label">To</label>
                        <input type="text" class="form-control" id="to" name="to" required>
                    </div>
                    <div class="mb-3">
                        <label for="fare" class="form-label">Fare</label>
                        <input type="number" class="form-control" id="fare" name="fare" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
