<!-- Delete Route Modal -->
@foreach ($routes as $route)
<div class="modal fade" id="deleteRouteModal{{ $route->id }}" tabindex="-1" aria-labelledby="deleteRouteModalLabel{{ $route->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRouteModalLabel{{ $route->id }}">Delete Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('routes.destroy', $route->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete the route from <strong>{{ $route->from }}</strong> to <strong>{{ $route->to }}</strong>?</p>
                    <p>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
