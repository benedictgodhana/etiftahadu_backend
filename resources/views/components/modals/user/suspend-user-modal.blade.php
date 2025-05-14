
<!-- Suspend User Modal -->
<div class="modal fade" id="suspendUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="suspendUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="suspendUserModalLabel{{ $user->id }}">
                    <i class="fas fa-ban me-2"></i>Suspend User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.suspend', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-lock fa-4x text-secondary mb-3"></i>
                        <h5>Are you sure you want to suspend this user?</h5>
                        <p class="text-muted">User: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
                        <p class="text-danger">The user will be unable to log in while suspended.</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="suspension_reason{{ $user->id }}" class="form-label">Reason for Suspension <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="suspension_reason{{ $user->id }}" name="reason" rows="3" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="suspension_duration{{ $user->id }}" class="form-label">Suspension Duration</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="suspension_duration{{ $user->id }}" name="duration" min="1" value="30">
                            <select class="form-select" name="duration_unit">
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                                <option value="indefinite">Indefinite</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifySuspension{{ $user->id }}" name="notify_user" value="1" checked>
                        <label class="form-check-label" for="notifySuspension{{ $user->id }}">
                            Notify user by email
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i> Suspend User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
