
<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1" aria-labelledby="resetPasswordModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="resetPasswordModalLabel{{ $user->id }}">
                    <i class="fas fa-key text-dark me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.reset-password', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-lock fa-4x text-dark mb-3"></i>
                        <h5>Reset User Password</h5>
                        <p class="text-muted">User: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="reset_type" id="autoGenerate{{ $user->id }}" value="auto" checked>
                            <label class="form-check-label" for="autoGenerate{{ $user->id }}">
                                Auto-generate secure password
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="reset_type" id="manualPassword{{ $user->id }}" value="manual">
                            <label class="form-check-label" for="manualPassword{{ $user->id }}">
                                Set password manually
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-3" id="manualPasswordFields{{ $user->id }}" style="display: none;">
                        <label for="new_password{{ $user->id }}" class="form-label">New Password <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="new_password{{ $user->id }}" name="new_password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword{{ $user->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <label for="new_password_confirmation{{ $user->id }}" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation{{ $user->id }}" name="new_password_confirmation">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmNewPassword{{ $user->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="forcePasswordChange{{ $user->id }}" name="force_change" value="1" checked>
                        <label class="form-check-label" for="forcePasswordChange{{ $user->id }}">
                            Force user to change password on next login
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyPasswordReset{{ $user->id }}" name="notify_user" value="1" checked>
                        <label class="form-check-label" for="notifyPasswordReset{{ $user->id }}">
                            Send password reset email to user
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key me-1"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Show/hide password fields based on reset type selection
    document.querySelectorAll('input[name="reset_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const userId = this.id.replace(/[^0-9]/g, '');
            const manualFields = document.getElementById(`manualPasswordFields${userId}`);

            if (this.value === 'manual') {
                manualFields.style.display = 'block';
                document.getElementById(`new_password${userId}`).setAttribute('required', 'required');
                document.getElementById(`new_password_confirmation${userId}`).setAttribute('required', 'required');
            } else {
                manualFields.style.display = 'none';
                document.getElementById(`new_password${userId}`).removeAttribute('required');
                document.getElementById(`new_password_confirmation${userId}`).removeAttribute('required');
            }
        });
    });

    // Toggle password visibility
    document.querySelectorAll('[id^="toggle"]').forEach(button => {
        button.addEventListener('click', function() {
            const inputId = this.id.replace('toggle', '').toLowerCase();
            const input = document.getElementById(inputId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
