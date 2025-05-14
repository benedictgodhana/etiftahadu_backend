<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Management</title>
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

        .badge-active {
            background-color: #C6F6D5;
            color: #22543D;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-inactive {
            background-color: #E9D8FD;
            color: #553C9A;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-suspended {
            background-color: #FED7D7;
            color: #822727;
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

        .route-preview {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #f8f9fa;
            position: relative;
        }

        .route-preview .route-id {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
            color: #666;
        }

        .route-detail-row {
            margin-bottom: 8px;
        }

        .route-actions {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
    <x-app-layout>
        <div class="container-fluid py-4" x-data="{
            searchQuery: '',
            routes: [],
            filteredRoutes: [],
            init() {
                this.routes = Array.from(document.querySelectorAll('#routeTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            from: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            to: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            fare: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterRoutes();
            },
            filterRoutes() {
                const query = this.searchQuery.toLowerCase();
                this.filteredRoutes = this.routes.filter(route =>
                    route.from.includes(query) ||
                    route.to.includes(query) ||
                    route.fare.includes(query) ||
                    route.status.includes(query)
                );

                this.routes.forEach(route => {
                    if (this.filteredRoutes.includes(route)) {
                        route.element.classList.remove('d-none');
                    } else {
                        route.element.classList.add('d-none');
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
                        <i class="fas fa-route me-2" style="color: var(--primary-color)"></i>Route Management
                    </h1>
                    @can('create routes')
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addRouteModal">
                        <i class="fas fa-plus me-1"></i> Add New Route
                    </button>
                    @endcan
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Routes</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by from, to, fare..."
                                    x-model="searchQuery" @keyup="filterRoutes()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" @change="filterRoutes()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; filterRoutes();">
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
                                <th>From</th>
                                <th>To</th>
                                <th>Fare</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="routeTableBody">
                            @foreach ($routes as $route)
                            <tr data-id="{{ $route->id }}">
                                <td>{{ $route->from }}</td>
                                <td>{{ $route->to }}</td>
                                <td>{{ $route->fare }}</td>
                                <td>{{ $route->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="route-actions">
                                        @can('view routes')
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewRouteModal{{ $route->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @endcan

                                        @can('edit routes')
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editRouteModal{{ $route->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan

                                        @can('delete routes')
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteRouteModal{{ $route->id }}">
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
                    {{ $routes->links() }}
                </div>
            </div>
        </div>
    </x-app-layout>

    <!-- Include modals for routes management -->
    @include('components.modals.route.add-route-modal')
    @include('components.modals.route.edit-route-modal')
    @include('components.modals.route.delete-route-modal')
    @include('components.modals.route.view-route-modal')

    <!-- Bootstrap and Alpine JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script>
        // Route status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#routeTableBody tr');

            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(4)');
                const statusText = statusCell.textContent.trim().toLowerCase();

                if (statusValue === '' || statusText === statusValue) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html>
