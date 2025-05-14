<!-- resources/views/components/modals/edit-card-modal.blade.php -->
@foreach ($cards as $card)
<div class="modal fade" id="editCardModal{{ $card->id }}" tabindex="-1" aria-labelledby="editCardModalLabel{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCardModalLabel{{ $card->id }}">
                    <i class="fas fa-edit me-2" style="color: var(--primary-color)"></i>Edit Card
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cards.update', $card->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name{{ $card->id }}" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name{{ $card->id }}" name="name" value="{{ $card->name }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email{{ $card->id }}" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email{{ $card->id }}" name="email" value="{{ $card->email }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tel{{ $card->id }}" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="tel{{ $card->id }}" name="tel" value="{{ $card->tel }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="status{{ $card->id }}" class="form-label">Status</label>
                            <select class="form-select" id="status{{ $card->id }}" name="status" required>
                                <option value="active" {{ $card->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $card->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $card->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="serial_number{{ $card->id }}" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number{{ $card->id }}" name="serial_number" value="{{ $card->serial_number }}" required>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Card
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
