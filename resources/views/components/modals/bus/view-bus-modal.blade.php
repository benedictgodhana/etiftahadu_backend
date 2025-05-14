@foreach ($buses as $bus)
<div class="modal fade" id="viewBusModal{{ $bus->id }}" tabindex="-1" aria-labelledby="viewBusModalLabel{{ $bus->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewBusModalLabel{{ $bus->id }}">
                    <i class="fas fa-bus-alt me-2 text-primary"></i>View Bus Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="viewBusNumber{{ $bus->id }}" class="form-label">Bus Plate Number</label>
                        <input type="text" id="viewBusNumber{{ $bus->id }}" class="form-control" value="{{ $bus->plate_number }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="viewCapacity{{ $bus->id }}" class="form-label">Capacity</label>
                        <input type="number" id="viewCapacity{{ $bus->id }}" class="form-control" value="{{ $bus->capacity }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="viewDriverName{{ $bus->id }}" class="form-label">Driver Name</label>
                        <input type="text" id="viewDriverName{{ $bus->id }}" class="form-control" value="{{ $bus->driver_name }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="viewConductorName{{ $bus->id }}" class="form-label">Conductor Name</label>
                        <input type="text" id="viewConductorName{{ $bus->id }}" class="form-control" value="{{ $bus->conductor_name }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="viewStatus{{ $bus->id }}" class="form-label">Status</label>
                        <input type="text" id="viewStatus{{ $bus->id }}" class="form-control" value="{{ ucfirst($bus->status) }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="viewRoute{{ $bus->id }}" class="form-label">Route</label>
                        <input type="text" id="viewRoute{{ $bus->id }}" class="form-control" value="{{ $bus->route->from }} → {{ $bus->route->to }}" readonly>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    /* View Bus Modal Styling - Red and White Theme */

/* Modal Header */
#viewBusModal .modal-header {
  background-color: #ffffff;
  border-bottom: 2px solid #dc3545;
  padding: 1rem 1.5rem;
}

#viewBusModal .modal-title {
  font-weight: 600;
  color: #dc3545;
}

/* Modal Body */
#viewBusModal .modal-body {
  padding: 1.5rem;
  background-color: #ffffff;
}

/* Form Elements */
#viewBusModal .form-label {
  font-weight: 500;
  color: #212529;
  margin-bottom: 0.3rem;
}

#viewBusModal .form-control {
  border-radius: 0.375rem;
  border: 1px solid #ced4da;
  padding: 0.5rem 0.75rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

/* Read-only Inputs */
#viewBusModal .form-control[readonly] {
  background-color: #f8f9fa;
  cursor: not-allowed;
}

/* Modal Footer */
#viewBusModal .modal-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
  padding: 1rem 1.5rem;
}

/* Buttons */
#viewBusModal .btn-light {
  background-color: #ffffff;
  border-color: #dee2e6;
  color: #6c757d;
  font-weight: 500;
  padding: 0.5rem 1rem;
}

#viewBusModal .btn-light:hover {
  background-color: #f8f9fa;
  border-color: #dc3545;
  color: #dc3545;
}

/* Responsive adjustments */
@media (max-width: 767px) {
  #viewBusModal .modal-dialog {
    margin: 0.5rem;
  }

  #viewBusModal .col-md-6 {
    margin-bottom: 0.5rem;
  }
}

/* Status dropdown styling */
#viewBusModal #status option[value="active"] {
  color: #198754;
}

#viewBusModal #status option[value="inactive"] {
  color: #6c757d;
}

#viewBusModal #status option[value="suspended"] {
  color: #dc3545;
}

/* Icons styling */
#viewBusModal .fas {
  display: inline-block;
  vertical-align: middle;
  color: #dc3545;
}

</style>
