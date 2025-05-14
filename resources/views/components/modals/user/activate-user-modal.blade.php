
<!-- Activate User Modal -->

<div class="modal fade" id="activateUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="activateUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="activateUserModalLabel{{ $user->id }}">
                    <i class="fas fa-unlock text-success me-2"></i>Activate User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.activate', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-check fa-4x text-success mb-3"></i>
                        <h5>Activate User Account</h5>
                        <p class="text-muted">User: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
                        <p>This will change the user's status to "Active" and allow them to log in again.</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="activation_note{{ $user->id }}" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="activation_note{{ $user->id }}" name="note" rows="3"></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyActivation{{ $user->id }}" name="notify_user" value="1" checked>
                        <label class="form-check-label" for="notifyActivation{{ $user->id }}">
                            Notify user by email
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Activate User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
