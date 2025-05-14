
<!-- Edit User Modal -->
@foreach ($users as $user)

<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">
                    <i class="fas fa-user-edit text-warning me-2"></i>Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name{{ $user->id }}" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_email{{ $user->id }}" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="edit_email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_phone{{ $user->id }}" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="edit_phone{{ $user->id }}" name="phone" value="{{ $user->phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_status{{ $user->id }}" class="form-label">Status</label>
                                <select class="form-select" id="edit_status{{ $user->id }}" name="status">
                                    <option value="Active" {{ $user->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ $user->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Suspended" {{ $user->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
