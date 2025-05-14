<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles Management</title>
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
            background-color: #FED7D7;
            color: #822727;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-system {
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

        .role-actions {
            display: flex;
            gap: 8px;
        }

        .pagination {
            display: flex;
            list-style: none;
            border-radius: 0.25rem;
            margin: 1.5rem 0;
            padding: 0;
        }

        .pagination .page-item {
            margin: 0 2px;
        }

        .pagination .page-item:first-child .page-link {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .pagination .page-item:last-child .page-link {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .pagination .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #0044cc;
            background-color: #fff;
            border: 1px solid #dee2e6;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: 'FuturaLT', sans-serif;
            font-size: 14px;
        }

        .pagination .page-link:hover {
            z-index: 2;
            color: #002266;
            text-decoration: none;
            background-color: #f2f7ff;
            border-color: #0044cc;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #0044cc;
            border-color: #0044cc;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination-info {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 0.35rem 0.5rem;
                font-size: 12px;
            }

            .pagination-info {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <x-app-layout>
        <div class="container-fluid py-4" x-data="{
            searchQuery: '',
            roles: [],
            filteredRoles: [],
            init() {
                this.roles = Array.from(document.querySelectorAll('#rolesTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            name: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            description: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            permissions: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterRoles();
            },
            filterRoles() {
                const query = this.searchQuery.toLowerCase();
                this.filteredRoles = this.roles.filter(role =>
                    role.name.includes(query) ||
                    role.description.includes(query) ||
                    role.permissions.includes(query) ||
                    role.status.includes(query)
                );

                this.roles.forEach(role => {
                    if (this.filteredRoles.includes(role)) {
                        role.element.classList.remove('d-none');
                    } else {
                        role.element.classList.add('d-none');
                    }
                });
            }
        }">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Error Message --}}
            @if($errors->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Header and Main Button -->
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center page-header">
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-user-shield me-2" style="color: var(--primary-color)"></i>Roles Management
                    </h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus-circle me-1"></i> Add New Role
                    </button>
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Roles</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by role name, description, or permissions..."
                                    x-model="searchQuery" @keyup="filterRoles()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" @change="filterRoles()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="system">System</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; filterRoles();">
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
                                <th>Role Name</th>
                                <th>Description</th>
                                <th>Permissions</th>
                                <th>Status</th>
                                <th>Users</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rolesTableBody">
                            @foreach ($roles as $role)
                            <tr data-id="{{ $role->id }}">
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
    @foreach($role->permissions as $permission)
        <span class="badge bg-primary">{{ $permission->name }}</span>
    @endforeach
</td>                                <td><span class="badge badge-{{ strtolower($role->status) }}">{{ ucfirst($role->status) }}</span></td>
                                <td>{{ $role->users_count }}</td>
                                <td>{{ $role->created_at->format('d M Y') }}</td>
                                <td class="text-end role-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewRoleModal{{ $role->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @can('edit roles')
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan

                                    @can('delete roles')
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this role?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{$roles->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </x-app-layout>

    <!-- Include modals from external files -->
    @include('components.modals.roles.add-role-modal')
    @include('components.modals.roles.edit-role-modal')
    @include('components.modals.roles.delete-role-modal')
    @include('components.modals.roles.view-role-modal')

    <!-- Bootstrap and Alpine JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script>
        // Role status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#rolesTableBody tr');

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
