<!-- Delete User Modal -->
@foreach ($users as $user)

<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-slash fa-4x text-danger mb-3"></i>
                        <h5>Are you sure you want to delete this user?</h5>
                        <p class="text-muted">User: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
                        <p class="text-danger fw-bold">This action cannot be undone!</p>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete{{ $user->id }}" required>
                        <label class="form-check-label" for="confirmDelete{{ $user->id }}">
                            I understand this action is permanent
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
