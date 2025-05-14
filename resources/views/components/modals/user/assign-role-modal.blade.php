<!-- Assign Role Modal -->
@foreach ($users as $user)

<div class="modal fade" id="assignRoleModal{{ $user->id }}" tabindex="-1" aria-labelledby="assignRoleModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="assignRoleModalLabel{{ $user->id }}">
                    <i class="fas fa-user-tag text-success me-2"></i>Assign Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.assign-role', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="user-info mb-3">
                            <h6>{{ $user->name }}</h6>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                            <small>Current Role: <span class="badge bg-secondary">  @foreach ($user->roles as $role)
        {{ $role->name }}@if (!$loop->last), @endif
    @endforeach</span></small>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="role_id{{ $user->id }}" class="form-label">Select New Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_id{{ $user->id }}" name="role_id" required>
                            <option value="">Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="note{{ $user->id }}" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="note{{ $user->id }}" name="note" rows="3" placeholder="Add a note about this role change"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Assign Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
