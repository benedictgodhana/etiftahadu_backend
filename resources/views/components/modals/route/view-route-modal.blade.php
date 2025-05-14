<!-- View Route Modal -->
@foreach ($routes as $route)
<div class="modal fade" id="viewRouteModal{{ $route->id }}" tabindex="-1" aria-labelledby="viewRouteModalLabel{{ $route->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRouteModalLabel{{ $route->id }}">Route Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>From:</strong> {{ $route->from }}</p>
                <p><strong>To:</strong> {{ $route->to }}</p>
                <p><strong>Fare:</strong> {{ $route->fare }}</p>
                <p><strong>Created At:</strong> {{ $route->created_at->format('Y-m-d') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
