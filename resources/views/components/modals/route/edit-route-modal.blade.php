<!-- Edit Route Modal -->
@foreach ($routes as $route)
<div class="modal fade" id="editRouteModal{{ $route->id }}" tabindex="-1" aria-labelledby="editRouteModalLabel{{ $route->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRouteModalLabel{{ $route->id }}">Edit Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('routes.update', $route->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="from" class="form-label">From</label>
                        <input type="text" class="form-control" id="from" name="from" value="{{ $route->from }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="to" class="form-label">To</label>
                        <input type="text" class="form-control" id="to" name="to" value="{{ $route->to }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="fare" class="form-label">Fare</label>
                        <input type="number" class="form-control" id="fare" name="fare" value="{{ $route->fare }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" {{ $route->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $route->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ $route->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
