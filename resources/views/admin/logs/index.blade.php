<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs</title>
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
            --warning-color: #FFC107;
            --error-color: #DC3545;
            --info-color: #0DCAF0;
            --success-color: #198754;
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

        .badge-error {
            background-color: #FED7D7;
            color: #822727;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-warning {
            background-color: #FEF3C7;
            color: #854D0E;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-info {
            background-color: #E0F2FE;
            color: #075985;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-debug {
            background-color: #E0E7FF;
            color: #3730A3;
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

        .log-actions {
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

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .log-entry-error {
            border-left: 4px solid var(--error-color);
        }

        .log-entry-warning {
            border-left: 4px solid var(--warning-color);
        }

        .log-entry-info {
            border-left: 4px solid var(--info-color);
        }

        .log-entry-debug {
            border-left: 4px solid var(--success-color);
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
            logs: [],
            filteredLogs: [],
            init() {
                this.logs = Array.from(document.querySelectorAll('#logsTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            level: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            timestamp: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            source: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase(),
                            message: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase(),
                            user: row.querySelector('td:nth-child(5)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterLogs();
            },
            filterLogs() {
                const query = this.searchQuery.toLowerCase();
                const levelFilter = document.getElementById('levelFilter').value.toLowerCase();

                this.filteredLogs = this.logs.filter(log =>
                    (log.level.includes(query) ||
                    log.timestamp.includes(query) ||
                    log.source.includes(query) ||
                    log.message.includes(query) ||
                    log.user.includes(query)) &&
                    (levelFilter === '' || log.level === levelFilter)
                );

                this.logs.forEach(log => {
                    if (this.filteredLogs.includes(log)) {
                        log.element.classList.remove('d-none');
                    } else {
                        log.element.classList.add('d-none');
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
                        <i class="fas fa-list-alt me-2" style="color: var(--primary-color)"></i>System Logs
                    </h1>
                    <div>
                        <button class="btn btn-secondary me-2">
                            <i class="fas fa-download me-1"></i> Export Logs
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logSettingsModal">
                            <i class="fas fa-cog me-1"></i> Log Settings
                        </button>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Logs</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search logs by message, source, or user..."
                                    x-model="searchQuery" @keyup="filterLogs()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" @change="filterLogs()" id="levelFilter">
                                <option value="">All Levels</option>
                                <option value="error">Error</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                                <option value="debug">Debug</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; document.getElementById('levelFilter').value = ''; filterLogs();">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">From</span>
                                <input type="date" class="form-control" value="2025-04-01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">To</span>
                                <input type="date" class="form-control" value="2025-04-13">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" @click="filterLogs()">
                                <i class="fas fa-filter me-1"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Timestamp</th>
                                <th>Source</th>
                                <th>Message</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <tr data-id="1" class="log-entry-error">
                                <td><span class="badge badge-error">Error</span></td>
                                <td>13 Apr 2025 14:32:45</td>
                                <td>Authentication Service</td>
                                <td>Failed login attempt: Invalid credentials</td>
                                <td>user.test@example.com</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="2" class="log-entry-warning">
                                <td><span class="badge badge-warning">Warning</span></td>
                                <td>13 Apr 2025 12:15:36</td>
                                <td>Database Service</td>
                                <td>Slow query execution: Query took 2.5s to complete</td>
                                <td>system</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="3" class="log-entry-info">
                                <td><span class="badge badge-info">Info</span></td>
                                <td>13 Apr 2025 10:42:18</td>
                                <td>User Management</td>
                                <td>User profile updated: Changed email address</td>
                                <td>jane.doe@example.com</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="4" class="log-entry-debug">
                                <td><span class="badge badge-debug">Debug</span></td>
                                <td>13 Apr 2025 09:05:22</td>
                                <td>API Gateway</td>
                                <td>Request processed: GET /api/v1/users/profile</td>
                                <td>system</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal4">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="5" class="log-entry-error">
                                <td><span class="badge badge-error">Error</span></td>
                                <td>12 Apr 2025 23:17:54</td>
                                <td>File Service</td>
                                <td>File upload failed: Storage limit exceeded</td>
                                <td>john.smith@example.com</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal5">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="6" class="log-entry-warning">
                                <td><span class="badge badge-warning">Warning</span></td>
                                <td>12 Apr 2025 18:29:41</td>
                                <td>Payment Service</td>
                                <td>Payment attempt retry: Gateway timeout</td>
                                <td>alice.jones@example.com</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal6">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="7" class="log-entry-info">
                                <td><span class="badge badge-info">Info</span></td>
                                <td>12 Apr 2025 15:03:12</td>
                                <td>Notification Service</td>
                                <td>Email notification sent: Password reset request</td>
                                <td>robert.johnson@example.com</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal7">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="8" class="log-entry-debug">
                                <td><span class="badge badge-debug">Debug</span></td>
                                <td>12 Apr 2025 11:47:30</td>
                                <td>Cache Service</td>
                                <td>Cache refreshed: User permissions updated</td>
                                <td>system</td>
                                <td class="text-end log-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal8">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="pagination-info">
                        Showing 1-8 of 256 log entries
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Log Statistics -->
            <div class="card p-4 mt-4">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-bar me-2" style="color: var(--primary-color)"></i>Log Statistics
                </h2>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title text-danger">Error Logs</h5>
                                <div class="display-4 mb-2">42</div>
                                <p class="card-text text-muted">Last 7 days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title text-warning">Warning Logs</h5>
                                <div class="display-4 mb-2">87</div>
                                <p class="card-text text-muted">Last 7 days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title text-info">Info Logs</h5>
                                <div class="display-4 mb-2">193</div>
                                <p class="card-text text-muted">Last 7 days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title text-success">Debug Logs</h5>
                                <div class="display-4 mb-2">524</div>
                                <p class="card-text text-muted">Last 7 days</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Most Common Error Sources</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Authentication Service
                                        <span class="badge bg-danger rounded-pill">18</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        File Service
                                        <span class="badge bg-danger rounded-pill">12</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Payment Service
                                        <span class="badge bg-danger rounded-pill">7</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Database Service
                                        <span class="badge bg-danger rounded-pill">5</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Log Volume Trend</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center text-muted">
                                    <i class="fas fa-chart-line fa-5x my-4"></i>
                                    <p>Log volume trend visualization would appear here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <!-- Log Settings Modal -->
    <div class="modal fade" id="logSettingsModal" tabindex="-1" aria-labelledby="logSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logSettingsModalLabel">Log Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="logSettingsForm">
                        <div class="mb-3">
                            <label class="form-label">Log Retention Period</label>
                            <select class="form-select" id="retentionPeriod">
                                <option value="7">7 days</option>
                                <option value="14">14 days</option>
                                <option value="30" selected>30 days</option>
                                <option value="60">60 days</option>
                                <option value="90">90 days</option>
                                <option value="180">180 days</option>
                                <option value="365">365 days</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minimum Log Level</label>
                            <select class="form-select" id="minimumLogLevel">
                                <option value="debug" selected>Debug (All logs)</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Log Sources</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="authServiceLog" checked>
                                    <label class="form-check-label" for="authServiceLog">
                                        Authentication Service
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dbServiceLog" checked>
                                    <label class="form-check-label" for="dbServiceLog">
                                        Database Service
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="userMgmtLog" checked>
                                    <label class="form-check-label" for="userMgmtLog">
                                        User Management
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="apiGatewayLog" checked>
                                    <label class="form-check-label" for="apiGatewayLog">
                                        API Gateway
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="fileServiceLog" checked>
                                    <label class="form-check-label" for="fileServiceLog">
                                        File Service
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="paymentServiceLog" checked>
                                    <label class="form-check-label" for="paymentServiceLog">
                                        Payment Service
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notificationServiceLog" checked>
                                    <label class="form-check-label" for="notificationServiceLog">
                                        Notification Service
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cacheServiceLog" checked>
                                    <label class="form-check-label" for="cacheServiceLog">
                                        Cache Service
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Log Notifications</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="errorNotifications" checked>
                                <label class="form-check-label" for="errorNotifications">
                                    <i class="fas fa-exclamation-circle me-2 text-danger"></i>Notify on Error Logs
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="warningNotifications" checked>
                                <label class="form-check-label" for="warningNotifications">
                                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Notify on Warning Logs
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="infoNotifications">
                                <label class="form-check-label" for="infoNotifications">
                                    <i class="fas fa-info-circle me-2 text-info"></i>Notify on Info Logs
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="debugNotifications">
                                <label class="form-check-label" for="debugNotifications">
                                    <i class="fas fa-bug me-2 text-success"></i>Notify on Debug Logs
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Settings</button>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
