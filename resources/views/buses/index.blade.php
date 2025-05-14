<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --text-color: #333;
            --bg-color: #FFF;
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

        .table th {
            font-weight: 600;
            color: #555;
        }

        .badge-active, .badge-inactive, .badge-suspended {
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-active {
            background-color: #C6F6D5;
            color: #22543D;
        }

        .badge-inactive {
            background-color: #E9D8FD;
            color: #553C9A;
        }

        .badge-suspended {
            background-color: #FED7D7;
            color: #822727;
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
    </style>
</head>
<body>
    <x-app-layout>
        <div class="container-fluid py-4" x-data="{
            searchQuery: '',
            buses: [],
            filteredBuses: [],
            init() {
                this.buses = Array.from(document.querySelectorAll('#busTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            plate: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            capacity: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase()
                        };
                    });
                this.filterBuses();
            },
            filterBuses() {
                const query = this.searchQuery.toLowerCase();
                this.filteredBuses = this.buses.filter(bus =>
                    bus.plate.includes(query) ||
                    bus.capacity.includes(query) ||
                    bus.status.includes(query)
                );

                this.buses.forEach(bus => {
                    if (this.filteredBuses.includes(bus)) {
                        bus.element.classList.remove('d-none');
                    } else {
                        bus.element.classList.add('d-none');
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

            <!-- Header -->
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-bus me-2" style="color: var(--primary-color)"></i>Bus Management
                    </h1>
                    @can('create buses')
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addBusModal">
                        <i class="fas fa-plus me-1"></i> Add New Bus
                    </button>
                    @endcan
                </div>

                <!-- Search & Filter -->
                <div class="search-section">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-search me-2"></i>Search Buses</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by plate, capacity, status..."
                                    x-model="searchQuery" @keyup="filterBuses()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Filter by Status</label>
                            <select class="form-select" @change="filterBuses()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100 mt-3" @click="searchQuery = ''; filterBuses();">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                            <th>Plate Number</th>
            <th>Capacity</th>
            <th>Status</th>
            <th>Route</th>
            <th>Driver</th>
            <th>Conductor</th>
            <th>Created At</th>
            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="busTableBody">
                            @foreach ($buses as $bus)
                            <tr data-id="{{ $bus->id }}">
                            <td>{{ $bus->plate_number }}</td>
                <td>{{ $bus->capacity }}</td>
                <td>
                    <span class="badge
                        @if($bus->status == 'active') bg-success
                        @elseif($bus->status == 'inactive') bg-secondary
                        @elseif($bus->status == 'suspended') bg-danger
                        @endif">
                        {{ ucfirst($bus->status) }}
                    </span>
                </td>
                <td>{{ $bus->route->from }} → {{ $bus->route->to }}</td> <!-- Display Route -->
                <td>{{ $bus->driver_name }}</td> <!-- Display Driver Name -->
                <td>{{ $bus->conductor_name }}</td> <!-- Display Conductor Name -->
                <td>{{ $bus->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @can('view buses')
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewBusModal{{ $bus->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @endcan

                                        @can('edit buses')
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editBusModal{{ $bus->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan

                                        @can('delete buses')
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteBusModal{{ $bus->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $buses->links() }}
                </div>
            </div>
        </div>
    </x-app-layout>

    <!-- Modals -->
    @include('components.modals.bus.add-bus-modal')
    @include('components.modals.bus.edit-bus-modal')
    @include('components.modals.bus.delete-bus-modal')
    @include('components.modals.bus.view-bus-modal')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script>
        document.getElementById('statusFilter').addEventListener('change', function () {
            const statusValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#busTableBody tr');

            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(3)');
                const statusText = statusCell.textContent.trim().toLowerCase();

                if (statusValue === '' || statusText.includes(statusValue)) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html>
