<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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

        .user-preview {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #f8f9fa;
            position: relative;
        }

        .user-preview .user-id {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
            color: #666;
        }

        .user-detail-row {
            margin-bottom: 8px;
        }

        .user-actions {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
    <x-app-layout>
        <div class="container-fluid py-4" x-data="{
            searchQuery: '',
            users: [],
            filteredUsers: [],
            init() {
                this.users = Array.from(document.querySelectorAll('#userTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            name: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            email: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            role: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterUsers();
            },
            filterUsers() {
                const query = this.searchQuery.toLowerCase();
                this.filteredUsers = this.users.filter(user =>
                    user.name.includes(query) ||
                    user.email.includes(query) ||
                    user.role.includes(query) ||
                    user.status.includes(query)
                );

                this.users.forEach(user => {
                    if (this.filteredUsers.includes(user)) {
                        user.element.classList.remove('d-none');
                    } else {
                        user.element.classList.add('d-none');
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
                        <i class="fas fa-users me-2" style="color: var(--primary-color)"></i>User Management
                    </h1>
                    @can('create users')
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i> Add New User
                    </button>
                    @endcan
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Users</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by name, email, or role..."
                                    x-model="searchQuery" @keyup="filterUsers()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" @change="filterUsers()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; filterUsers();">
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Phone</th>
                                <th>Last Login</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @foreach ($users as $user)
                            <tr data-id="{{ $user->id }}">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
    @foreach ($user->roles as $role)
        {{ $role->name }}@if (!$loop->last), @endif
    @endforeach
</td>
                                <td>
                                    <span class="badge
                                        @if($user->status == 'Active') badge-active
                                        @elseif($user->status == 'Inactive') badge-inactive
                                        @else badge-suspended @endif">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->last_login ? $user->last_login->format('Y-m-d H:i') : 'Never' }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="user-actions">
                                        @can('view users')
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewUserModal{{ $user->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @endcan

                                        @can('edit users')
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan

                                        @can('assign roles')
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#assignRoleModal{{ $user->id }}">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        @endcan

                                        @can('suspend users')
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#suspendUserModal{{ $user->id }}">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        @endcan

                                        @can('activate users')
                                        <button class="btn btn-sm btn-light" data-bs-toggle="modal"
                                            data-bs-target="#activateUserModal{{ $user->id }}">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                        @endcan

                                        @can('reset password')
                                        <button class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#resetPasswordModal{{ $user->id }}">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        @endcan

                                        @can('delete users')
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteUserModal{{ $user->id }}">
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
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </x-app-layout>

<!-- Include modals from external files -->
@include('components.modals.user.add-user-modal')
@include('components.modals.user.edit-user-modal')
@include('components.modals.user.delete-user-modal')
@include('components.modals.user.view-user-modal')
@include('components.modals.user.assign-role-modal')
@include('components.modals.user.suspend-user-modal')
@include('components.modals.user.activate-user-modal')
@include('components.modals.user.reset-password-modal')

    <!-- Bootstrap and Alpine JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script>
        // User status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#userTableBody tr');

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
