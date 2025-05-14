<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addScheduleModalLabel">Add Commute Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('schedules.store') }}" method="POST">
        @csrf
        <div class="modal-body">

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="route_id" class="form-label">Select Route</label>
              <select class="form-select" name="route_id" id="route_id" required>
                <option value="" disabled selected>-- Choose Route --</option>
                @foreach($routes as $route)
                  <option value="{{ $route->id }}" data-fare="{{ $route->fare }}" data-route-number="{{ $route->id }}">
                    {{ $route->from }} → {{ $route->to }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="route_number" class="form-label">Route #</label>
              <input type="text" name="route_number" id="route_number" class="form-control" placeholder="E.g. R001" required readonly>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="departure_time" class="form-label">Departure Time</label>
              <input type="time" name="departure_time" id="departure_time" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label for="arrival_time" class="form-label">Arrival Time</label>
              <input type="time" name="arrival_time" id="arrival_time" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" name="status" id="status" required>
                <option value="" disabled selected>-- Select Status --</option>
                <option value="On Time">On Time</option>
                <option value="Cancelled">Cancelled</option>
                <option value="Delayed">Delayed</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="fare" class="form-label">Fare (Ksh)</label>
              <input type="number" step="0.01" name="fare" id="fare" class="form-control" placeholder="e.g. 150" required readonly>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Schedule</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // JavaScript to update fare and route number when a route is selected
  document.getElementById('route_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const fare = selectedOption.getAttribute('data-fare');
    const routeNumber = selectedOption.getAttribute('data-route-number');

    // Set the fare and route number
    document.getElementById('fare').value = fare;
    document.getElementById('route_number').value = routeNumber;
  });
</script>
