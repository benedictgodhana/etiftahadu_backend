<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commute Schedules</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4F46E5; /* Indigo */
            --primary-hover: #4338CA;
            --text-color: #333333;
            --bg-color: #FFFFFF;
            --light-gray: #F5F5F5;
        }

        body {
            background-color: var(--light-gray);
            color: var(--text-color);
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .page-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            font-weight: 600;
            color: #555;
        }

        .badge-ontime {
            background-color: #C6F6D5;
            color: #22543D;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-delayed {
            background-color: #FED7D7;
            color: #822727;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-cancelled {
            background-color: #E9D8FD;
            color: #553C9A;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .search-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .schedule-preview {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #f8f9fa;
            position: relative;
        }

        .schedule-preview .schedule-id {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
            color: #666;
        }

        .schedule-detail-row {
            margin-bottom: 8px;
        }

        .schedule-actions {
            display: flex;
            gap: 8px;
        }

        .time-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .route-number {
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
            background-color: #EDF2F7;
            color: #2D3748;
        }

        .quick-stats {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .quick-stat-item {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
        }

        .quick-stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2D3748;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #718096;
        }

        .filter-date {
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: #EDF2F7;
            display: flex;
            align-items: center;
        }

        .filter-date:hover {
            background-color: #E2E8F0;
        }

        .filter-date i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <x-app-layout>
        <div class="container-fluid py-4" x-data="{
            searchQuery: '',
            schedules: [],
            filteredSchedules: [],
            selectedDate: new Date().toISOString().split('T')[0],
            init() {
                this.schedules = Array.from(document.querySelectorAll('#scheduleTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            route: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            from: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            to: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase(),
                            departure: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(6)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterSchedules();
            },
            filterSchedules() {
                const query = this.searchQuery.toLowerCase();
                this.filteredSchedules = this.schedules.filter(schedule =>
                    schedule.route.includes(query) ||
                    schedule.from.includes(query) ||
                    schedule.to.includes(query) ||
                    schedule.departure.includes(query) ||
                    schedule.status.includes(query)
                );

                this.schedules.forEach(schedule => {
                    if (this.filteredSchedules.includes(schedule)) {
                        schedule.element.classList.remove('d-none');
                    } else {
                        schedule.element.classList.add('d-none');
                    }
                });
            }
        }">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Header and Main Button -->
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center page-header">
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-bus me-2" style="color: var(--primary-color)"></i>Commute Schedules
                    </h1>
                    <div class="d-flex gap-2">
                        <div class="filter-date" x-data="{}" @click="document.getElementById('datePicker').showPicker()">
                            <i class="far fa-calendar-alt"></i>
                            <span x-text="selectedDate"></span>
                            <input type="date" id="datePicker" class="d-none" x-model="selectedDate">
                        </div>
                        @can('create schedules')
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                            <i class="fas fa-plus me-1"></i> Add Trip
                        </button>
                        @endcan
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="quick-stats mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="quick-stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div class="stat-value">{{ $totalRoutes ?? 14 }}</div>
                                <div class="stat-label">Total Routes</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="quick-stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-value">{{ $scheduledToday ?? 26 }}</div>
                                <div class="stat-label">Scheduled Today</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="quick-stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-value">{{ $delayedTrips ?? 3 }}</div>
                                <div class="stat-label">Delayed Trips</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="quick-stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <div class="stat-value">{{ $cancelledTrips ?? 1 }}</div>
                                <div class="stat-label">Cancelled Trips</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Schedules</h5>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by route, location, time..."
                                    x-model="searchQuery" @keyup="filterSchedules()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" @change="filterSchedules()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="on time">On Time</option>
                                <option value="delayed">Delayed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="timeFilter">
                                <option value="">All Times</option>
                                <option value="morning">Morning</option>
                                <option value="afternoon">Afternoon</option>
                                <option value="evening">Evening</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; filterSchedules();">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Route #</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Status</th>
                                <th>Fare</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            @forelse ($schedules ?? [] as $schedule)
                            <tr data-id="{{ $schedule->id }}">
                                <td><span class="route-number">{{ $schedule->route_number }}</span></td>
                                <td>{{ $schedule->route->from }}</td>
<td>{{ $schedule->route->to }}</td>

                                <td><span class="time-badge">{{ $schedule->departure_time }}</span></td>
                                <td><span class="time-badge">{{ $schedule->arrival_time }}</span></td>
                                <td>
                                    <span class="badge-{{ strtolower(str_replace(' ', '', $schedule->status)) }}">
                                        {{ $schedule->status }}
                                    </span>
                                </td>
                                <td>${{ number_format($schedule->fare, 2) }}</td>
                                <td>
                                    <div class="schedule-actions">
                                        @can('view schedules')
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewScheduleModal{{ $schedule->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @endcan

                                        @can('book trips')
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#bookTripModal{{ $schedule->id }}">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <!-- Example data (remove in production) -->
                            <tr data-id="1">
                                <td><span class="route-number">101</span></td>
                                <td>Central Station</td>
                                <td>Business Park</td>
                                <td><span class="time-badge">07:30 AM</span></td>
                                <td><span class="time-badge">08:15 AM</span></td>
                                <td><span class="badge-ontime">On Time</span></td>
                                <td>$3.50</td>
                                <td>
                                    <div class="schedule-actions">
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-id="2">
                                <td><span class="route-number">205</span></td>
                                <td>Metro Plaza</td>
                                <td>University</td>
                                <td><span class="time-badge">09:15 AM</span></td>
                                <td><span class="time-badge">09:45 AM</span></td>
                                <td><span class="badge-delayed">Delayed</span></td>
                                <td>$2.75</td>
                                <td>
                                    <div class="schedule-actions">
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-id="3">
                                <td><span class="route-number">303</span></td>
                                <td>Downtown</td>
                                <td>Shopping Mall</td>
                                <td><span class="time-badge">12:00 PM</span></td>
                                <td><span class="time-badge">12:30 PM</span></td>
                                <td><span class="badge-ontime">On Time</span></td>
                                <td>$2.25</td>
                                <td>
                                    <div class="schedule-actions">
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-id="4">
                                <td><span class="route-number">101</span></td>
                                <td>Business Park</td>
                                <td>Central Station</td>
                                <td><span class="time-badge">05:30 PM</span></td>
                                <td><span class="time-badge">06:15 PM</span></td>
                                <td><span class="badge-cancelled">Cancelled</span></td>
                                <td>$3.50</td>
                                <td>
                                    <div class="schedule-actions">
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" disabled>
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-id="5">
                                <td><span class="route-number">405</span></td>
                                <td>Airport</td>
                                <td>Central Station</td>
                                <td><span class="time-badge">07:00 PM</span></td>
                                <td><span class="time-badge">08:00 PM</span></td>
                                <td><span class="badge-ontime">On Time</span></td>
                                <td>$5.00</td>
                                <td>
                                    <div class="schedule-actions">
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $schedules->links() ?? '' }}
                </div>
            </div>

            <div class="card p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="far fa-calendar-alt me-2" style="color: var(--primary-color)"></i>Weekly Schedule Overview</h4>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary active">This Week</button>
            <button type="button" class="btn btn-outline-primary">Next Week</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center bg-light">
                    <th>Time</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                    <th>Sunday</th>
                </tr>
            </thead>
            <tbody>
    @foreach (['Morning', 'Afternoon', 'Evening'] as $timeOfDay)
        <tr>
            <td class="fw-bold text-center">
                {{ $timeOfDay }}<br>
                <small class="text-muted">{{ $getTimeRange($timeOfDay) }}</small>
            </td>

            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                <td class="text-center">
                    @foreach ($commuteschedules->where('day_of_week', $day)->where('time_of_day', $timeOfDay) as $schedule)
                    <div class="mb-2 p-1 bg-light rounded">
    <div class="route-number fw-bold">
        {{ $schedule->route->id ?? 'No Route' }}
    </div>
    <div class="small">
        {{ $schedule->route->from ?? 'Unknown' }} → {{ $schedule->route->to ?? 'Unknown' }}
    </div>
    <span class="badge bg-primary mt-1">
        Departure: {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
    </span>
    <span class="badge bg-success mt-1">
        Arrival: {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}
    </span>
</div>

                    @endforeach
                </td>
            @endforeach
        </tr>
    @endforeach
</tbody>

        </table>
    </div>
</div>

    </x-app-layout>

    <!-- Include modals for schedule management -->
    @include('components.modals.schedule.view-schedule-modal')
    @include('components.modals.schedule.book-trip-modal')
    @include('components.modals.schedule.add-schedule-modal')

    <!-- Bootstrap and Alpine JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script>
        // Schedule status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#scheduleTableBody tr');

            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(6)');
                const statusText = statusCell.textContent.trim().toLowerCase();

                if (statusValue === '' || statusText.includes(statusValue)) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        });

        // Time period filter functionality
        document.getElementById('timeFilter').addEventListener('change', function() {
            const timeValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#scheduleTableBody tr');

            rows.forEach(row => {
                const timeCell = row.querySelector('td:nth-child(4)');
                const timeText = timeCell.textContent.trim().toLowerCase();
                const timePeriod = timeText.split(' ')[1].toLowerCase();
                const timeHour = parseInt(timeText.split(':')[0]);
                let isInTimePeriod = false;
                if (timeValue === 'morning' && timeHour >= 6 && timeHour < 12) {
                    isInTimePeriod = true;
                } else if (timeValue === 'afternoon' && timeHour >= 12 && timeHour < 18) {
                    isInTimePeriod = true;
                } else if (timeValue === 'evening' && timeHour >= 18 && timeHour < 24) {
                    isInTimePeriod = true;
                }
                if (timeValue === '' || isInTimePeriod) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html>

