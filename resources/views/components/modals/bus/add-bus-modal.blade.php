<div class="modal fade" id="addBusModal" tabindex="-1" aria-labelledby="addBusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('buses.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBusModalLabel">
                        <i class="fas fa-bus-alt me-2 text-primary"></i>Add New Bus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="busNumber" class="form-label">Bus Plate Number</label>
                            <input type="text" name="plate_number" id="busNumber" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" name="capacity" id="capacity" class="form-control" required min="1">
                        </div>

                        <div class="col-md-6">
                            <label for="driverName" class="form-label">Driver Name (optional)</label>
                            <input type="text" name="driver_name" id="driverName" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="conductorName" class="form-label">Conductor Name (optional)</label>
                            <input type="text" name="conductor_name" id="conductorName" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="route" class="form-label">Route</label>
                            <select name="route_id" id="route" class="form-select" required>
                                <option value="">Select Route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->from }} → {{ $route->to }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Bus
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<style>

    /* Bus Management Modal Styling - Red and White Theme */

/* Modal Header */
#addBusModal .modal-header {
  background-color: #ffffff;
  border-bottom: 2px solid #dc3545;
  padding: 1rem 1.5rem;
}

#addBusModal .modal-title {
  font-weight: 600;
  color: #dc3545;
}

/* Modal Body */
#addBusModal .modal-body {
  padding: 1.5rem;
  background-color: #ffffff;
}

/* Form Elements */
#addBusModal .form-label {
  font-weight: 500;
  color: #212529;
  margin-bottom: 0.3rem;
}

#addBusModal .form-control,
#addBusModal .form-select {
  border-radius: 0.375rem;
  border: 1px solid #ced4da;
  padding: 0.5rem 0.75rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

#addBusModal .form-control:focus,
#addBusModal .form-select:focus {
  border-color: #f1aeb5;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

/* Modal Footer */
#addBusModal .modal-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
  padding: 1rem 1.5rem;
}

/* Buttons */
#addBusModal .btn-primary {
  background-color: #dc3545;
  border-color: #dc3545;
  font-weight: 500;
  padding: 0.5rem 1rem;
  transition: all 0.15s ease-in-out;
}

#addBusModal .btn-primary:hover {
  background-color: #c82333;
  border-color: #bd2130;
  transform: translateY(-1px);
}

#addBusModal .btn-light {
  background-color: #ffffff;
  border-color: #dee2e6;
  color: #6c757d;
  font-weight: 500;
  padding: 0.5rem 1rem;
}

#addBusModal .btn-light:hover {
  background-color: #f8f9fa;
  border-color: #dc3545;
  color: #dc3545;
}

/* Responsive adjustments */
@media (max-width: 767px) {
  #addBusModal .modal-dialog {
    margin: 0.5rem;
  }

  #addBusModal .col-md-6 {
    margin-bottom: 0.5rem;
  }
}

/* Status dropdown styling */
#addBusModal #status option[value="active"] {
  color: #198754;
}

#addBusModal #status option[value="inactive"] {
  color: #6c757d;
}

#addBusModal #status option[value="suspended"] {
  color: #dc3545;
}

/* Icons styling */
#addBusModal .fas {
  display: inline-block;
  vertical-align: middle;
  color: #dc3545;
}

/* Form validation styling */
#addBusModal .form-control:invalid:focus,
#addBusModal .form-select:invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

/* Additional red & white theme elements */
#addBusModal .modal-content {
  border-top: 4px solid #dc3545;
}

#addBusModal .modal-title i {
  color: #dc3545;
}

#addBusModal .form-label::after {
  content: "";
  display: block;
  width: 25px;
  height: 2px;
  background-color: rgba(220, 53, 69, 0.3);
  margin-top: 2px;
  margin-bottom: 5px;
}
</style>
