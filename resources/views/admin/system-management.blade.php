<!-- resources/views/admin/system-management.blade.php -->
<x-app-layout>
    <div class="container-fluid py-4">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center page-header">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-cogs me-2" style="color: #4F46E5;"></i>System Management
                </h1>
                <div>
                    <button class="btn btn-danger" id="refresh-cache">
                        <i class="fas fa-sync me-1"></i> Refresh Cache
                    </button>
                </div>
            </div>

            <div class="row mt-4">
                <!-- System Management Cards -->
                @can('manage roles')
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                <i class="fas fa-users-cog fa-2x text-primary"></i>
                            </div>
                            <h4 class="card-title">Role Management</h4>
                            <p class="card-text text-muted">Manage user roles and permissions across the system</p>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary mt-3">
                                <i class="fas fa-arrow-right me-1"></i> Manage Roles
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                @can('configure settings')
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                <i class="fas fa-sliders-h fa-2x text-success"></i>
                            </div>
                            <h4 class="card-title">System Settings</h4>
                            <p class="card-text text-muted">Configure application-wide settings and preferences</p>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-success mt-3">
                                <i class="fas fa-cog me-1"></i> Configure Settings
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                @can('manage notifications')
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                <i class="fas fa-bell fa-2x text-warning"></i>
                            </div>
                            <h4 class="card-title">Notification Management</h4>
                            <p class="card-text text-muted">Configure system notifications and user alerts</p>
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-warning mt-3">
                                <i class="fas fa-bell me-1"></i> Manage Notifications
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view logs')
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                <i class="fas fa-clipboard-list fa-2x text-info"></i>
                            </div>
                            <h4 class="card-title">System Logs</h4>
                            <p class="card-text text-muted">View and analyze system logs and activity history</p>
                            <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-info mt-3">
                                <i class="fas fa-list me-1"></i> View Logs
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

               

                @can('manage database')
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                <i class="fas fa-database fa-2x text-secondary"></i>
                            </div>
                            <h4 class="card-title">Database Management</h4>
                            <p class="card-text text-muted">Manage database operations, backups and maintenance</p>
                            <a href="{{ route('admin.database.index') }}" class="btn btn-outline-secondary mt-3">
                                <i class="fas fa-hdd me-1"></i> Manage Database
                            </a>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>

        <!-- System Health Card -->
        <div class="card p-4 mb-4">
            <h5 class="mb-3"><i class="fas fa-heartbeat me-2 text-danger"></i>System Health</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">CPU Usage</h6>
                                    <h4 class="mb-0">{{ $systemHealth['cpu_usage'] }}%</h4>
                                </div>
                                <div class="rounded-circle p-2 bg-primary bg-opacity-10">
                                    <i class="fas fa-microchip text-primary"></i>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $systemHealth['cpu_usage'] }}%;"
                                    aria-valuenow="{{ $systemHealth['cpu_usage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Memory Usage</h6>
                                    <h4 class="mb-0">{{ $systemHealth['memory_usage'] }}%</h4>
                                </div>
                                <div class="rounded-circle p-2 bg-success bg-opacity-10">
                                    <i class="fas fa-memory text-success"></i>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $systemHealth['memory_usage'] }}%;"
                                    aria-valuenow="{{ $systemHealth['memory_usage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Disk Usage</h6>
                                    <h4 class="mb-0">{{ $systemHealth['disk_usage'] }}%</h4>
                                </div>
                                <div class="rounded-circle p-2 bg-warning bg-opacity-10">
                                    <i class="fas fa-hdd text-warning"></i>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $systemHealth['disk_usage'] }}%;"
                                    aria-valuenow="{{ $systemHealth['disk_usage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Uptime</h6>
                                    <h4 class="mb-0">{{ $systemHealth['uptime'] }}</h4>
                                </div>
                                <div class="rounded-circle p-2 bg-info bg-opacity-10">
                                    <i class="fas fa-clock text-info"></i>
                                </div>
                            </div>
                            <div class="mt-2 text-muted small">
                                Last reboot: {{ $systemHealth['last_reboot'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent System Activities -->
        <div class="card p-4">
            <h5 class="mb-3"><i class="fas fa-history me-2 text-primary"></i>Recent System Activities</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>User</th>
                            <th>IP Address</th>
                            <th>Timestamp</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($activity['type'] == 'login')
                                        <span class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                            <i class="fas fa-sign-in-alt text-success"></i>
                                        </span>
                                    @elseif($activity['type'] == 'settings')
                                        <span class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="fas fa-cog text-primary"></i>
                                        </span>
                                    @elseif($activity['type'] == 'database')
                                        <span class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">
                                            <i class="fas fa-database text-warning"></i>
                                        </span>
                                    @else
                                        <span class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                            <i class="fas fa-info-circle text-info"></i>
                                        </span>
                                    @endif
                                    {{ $activity['description'] }}
                                </div>
                            </td>
                            <td>{{ $activity['user'] }}</td>
                            <td>{{ $activity['ip_address'] }}</td>
                            <td>{{ $activity['timestamp'] }}</td>
                            <td>
                                @if($activity['status'] == 'success')
                                    <span class="badge bg-success">Success</span>
                                @elseif($activity['status'] == 'warning')
                                    <span class="badge bg-warning text-dark">Warning</span>
                                @elseif($activity['status'] == 'error')
                                    <span class="badge bg-danger">Error</span>
                                @else
                                    <span class="badge bg-secondary">Info</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-primary btn-sm">
                    View All Activities
                </a>
            </div>
        </div>
    </div>

    <!-- Modal for Database Backup -->
    <div class="modal fade" id="backupModal" tabindex="-1" aria-labelledby="backupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="backupModalLabel">Create Database Backup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.database.backup') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="backup_name" class="form-label">Backup Name</label>
                            <input type="text" class="form-control" id="backup_name" name="backup_name"
                                value="backup_{{ date('Y_m_d_His') }}" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_files" name="include_files" value="1">
                                <label class="form-check-label" for="include_files">
                                    Include file storage
                                </label>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i> This process may take several minutes depending on the database size.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i> Create Backup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for System Management -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Refresh cache button functionality
            document.getElementById('refresh-cache').addEventListener('click', function() {
                fetch('{{ route("admin.cache.refresh") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Show success notification
                        const alertHtml = `
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
                    } else {
                        // Show error notification
                        const alertHtml = `
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</x-app-layout>
