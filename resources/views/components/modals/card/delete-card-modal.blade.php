<!-- resources/views/components/modals/delete-card-modal.blade.php -->
@foreach ($cards as $card)
<div class="modal fade" id="deleteCardModal{{ $card->id }}" tabindex="-1" aria-labelledby="deleteCardModalLabel{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCardModalLabel{{ $card->id }}">
                    <i class="fas fa-trash-alt me-2" style="color: #dc3545"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the card for <strong>{{ $card->name }}</strong>?</p>
                <div class="card-preview mb-3">
                    <span class="serial-number">{{ $card->serial_number }}</span>
                    <div class="card-detail-row"><strong>Name:</strong> {{ $card->name }}</div>
                    <div class="card-detail-row"><strong>Email:</strong> {{ $card->email }}</div>
                    <div class="card-detail-row"><strong>Telephone:</strong> {{ $card->tel }}</div>
                    <div class="card-detail-row">
                        <strong>Status:</strong>
                        <span class="badge
                            @if($card->status == 'active') badge-active
                            @elseif($card->status == 'inactive') badge-inactive
                            @else badge-suspended @endif">
                            {{ ucfirst($card->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('cards.destroy', $card->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete Card
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
