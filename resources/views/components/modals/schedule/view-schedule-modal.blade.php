@foreach($commuteschedules as $schedule)
    <!-- View Schedule Modal -->
    <div class="modal fade" id="viewScheduleModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="viewScheduleModalLabel{{ $schedule->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewScheduleModalLabel{{ $schedule->id }}">Schedule Details for                             {{ $schedule->route->from ?? 'Unknown' }} → {{ $schedule->route->to ?? 'Unknown' }}
</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Route:</strong>
                        <div>{{ $schedule->route->id ?? 'No Route' }} -
                            {{ $schedule->route->from ?? 'Unknown' }} → {{ $schedule->route->to ?? 'Unknown' }}
                        </div>
                    </div>

                    <!-- Departure and Arrival as badges -->
                    <div class="mb-3">
                        <span class="badge bg-primary">
                            Departure: {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                        </span>
                        <span class="badge bg-success">
                            Arrival: {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}
                        </span>
                    </div>

                    <!-- Additional schedule information -->
                    <div>
                        <strong>Time of Day:</strong> {{ $schedule->time_of_day }}
                    </div>
                    <div>
                        <strong>Day of Week:</strong> {{ $schedule->day_of_week }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
