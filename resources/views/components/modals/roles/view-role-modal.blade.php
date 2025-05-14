<!-- Inside your existing viewRoleModal structure -->

@foreach ($roles as $role)
<div class="modal fade" id="viewRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Role Details: {{ $role->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Other role details you might already have -->

                <!-- Permissions Section -->
                <div class="mt-3">
                    <h6>Permissions ({{ $role->permissions_count }})</h6>
                    @if($role->permissions->count() > 0)
                        <div class="permissions-container">
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-primary mb-1 me-1">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p>This role has no permissions assigned.</p>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endforeach
