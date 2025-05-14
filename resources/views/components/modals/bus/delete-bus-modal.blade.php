@foreach ($buses as $bus)
<div class="modal fade" id="deleteBusModal{{ $bus->id }}" tabindex="-1" aria-labelledby="deleteBusModalLabel{{ $bus->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBusModalLabel{{ $bus->id }}">
                    <i class="fas fa-trash-alt me-2 text-danger"></i>Delete Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="text-danger">
                    Are you sure you want to delete the bus with plate number <strong>{{ $bus->plate_number }}</strong>?
                </p>
                <p>This action cannot be undone.</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('buses.destroy', $bus->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Bus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach


<style>
    /* Delete Bus Modal Styling - Red and White Theme */

/* Modal Header */
#deleteBusModal .modal-header {
  background-color: #ffffff;
  border-bottom: 2px solid #dc3545;
  padding: 1rem 1.5rem;
}

#deleteBusModal .modal-title {
  font-weight: 600;
  color: #dc3545;
}

/* Modal Body */
#deleteBusModal .modal-body {
  padding: 1.5rem;
  background-color: #ffffff;
}

#deleteBusModal .text-danger {
  font-size: 1.1rem;
  font-weight: 600;
}

/* Modal Footer */
#deleteBusModal .modal-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
  padding: 1rem 1.5rem;
}

/* Buttons */
#deleteBusModal .btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
  font-weight: 500;
  padding: 0.5rem 1rem;
  transition: all 0.15s ease-in-out;
}

#deleteBusModal .btn-danger:hover {
  background-color: #c82333;
  border-color: #bd2130;
  transform: translateY(-1px);
}

#deleteBusModal .btn-light {
  background-color: #ffffff;
  border-color: #dee2e6;
  color: #6c757d;
  font-weight: 500;
  padding: 0.5rem 1rem;
}

#deleteBusModal .btn-light:hover {
  background-color: #f8f9fa;
  border-color: #dc3545;
  color: #dc3545;
}

/* Responsive adjustments */
@media (max-width: 767px) {
  #deleteBusModal .modal-dialog {
    margin: 0.5rem;
  }
}

</style>
