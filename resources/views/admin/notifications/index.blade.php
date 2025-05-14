<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Settings</title>
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

        .badge-enabled {
            background-color: #C6F6D5;
            color: #22543D;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-disabled {
            background-color: #FED7D7;
            color: #822727;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-default {
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

        .notification-actions {
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
            notifications: [],
            filteredNotifications: [],
            init() {
                this.notifications = Array.from(document.querySelectorAll('#notificationsTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            type: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            description: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            channels: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterNotifications();
            },
            filterNotifications() {
                const query = this.searchQuery.toLowerCase();
                this.filteredNotifications = this.notifications.filter(notification =>
                    notification.type.includes(query) ||
                    notification.description.includes(query) ||
                    notification.channels.includes(query) ||
                    notification.status.includes(query)
                );

                this.notifications.forEach(notification => {
                    if (this.filteredNotifications.includes(notification)) {
                        notification.element.classList.remove('d-none');
                    } else {
                        notification.element.classList.add('d-none');
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
                        <i class="fas fa-bell me-2" style="color: var(--primary-color)"></i>Notification Settings
                    </h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                        <i class="fas fa-plus-circle me-1"></i> Add Custom Notification
                    </button>
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Notifications</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by notification type, description, or channels..."
                                    x-model="searchQuery" @keyup="filterNotifications()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" @change="filterNotifications()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="enabled">Enabled</option>
                                <option value="disabled">Disabled</option>
                                <option value="default">Default</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; filterNotifications();">
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
                                <th>Notification Type</th>
                                <th>Description</th>
                                <th>Channels</th>
                                <th>Status</th>
                                <th>Frequency</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="notificationsTableBody">
                            <tr data-id="1">
                                <td>Account Login</td>
                                <td>Notify when a new login is detected on your account</td>
                                <td>
                                    <span class="badge bg-primary">Email</span>
                                    <span class="badge bg-primary">Push</span>
                                </td>
                                <td><span class="badge badge-enabled">Enabled</span></td>
                                <td>Immediate</td>
                                <td>12 Apr 2025</td>
                                <td class="text-end notification-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewNotificationModal1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editNotificationModal1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="2">
                                <td>Password Change</td>
                                <td>Notify when your password is changed</td>
                                <td>
                                    <span class="badge bg-primary">Email</span>
                                    <span class="badge bg-primary">SMS</span>
                                </td>
                                <td><span class="badge badge-enabled">Enabled</span></td>
                                <td>Immediate</td>
                                <td>10 Apr 2025</td>
                                <td class="text-end notification-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewNotificationModal2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editNotificationModal2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="3">
                                <td>New Message</td>
                                <td>Notify when you receive a new message</td>
                                <td>
                                    <span class="badge bg-primary">Push</span>
                                    <span class="badge bg-primary">In-App</span>
                                </td>
                                <td><span class="badge badge-enabled">Enabled</span></td>
                                <td>Immediate</td>
                                <td>08 Apr 2025</td>
                                <td class="text-end notification-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewNotificationModal3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editNotificationModal3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="4">
                                <td>Weekly Digest</td>
                                <td>Weekly summary of activity on your account</td>
                                <td>
                                    <span class="badge bg-primary">Email</span>
                                </td>
                                <td><span class="badge badge-disabled">Disabled</span></td>
                                <td>Weekly</td>
                                <td>05 Apr 2025</td>
                                <td class="text-end notification-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewNotificationModal4">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editNotificationModal4">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="5">
                                <td>System Updates</td>
                                <td>Important system updates and maintenance notifications</td>
                                <td>
                                    <span class="badge bg-primary">Email</span>
                                    <span class="badge bg-primary">SMS</span>
                                    <span class="badge bg-primary">Push</span>
                                </td>
                                <td><span class="badge badge-default">Default</span></td>
                                <td>As needed</td>
                                <td>01 Apr 2025</td>
                                <td class="text-end notification-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewNotificationModal5">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editNotificationModal5">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr data-id="6">
                                <td>Marketing Updates</td>
                                <td>Promotional offers, new features, and product updates</td>
                                <td>
                                    <span class="badge bg-primary">Email</span>
                                </td>
                                <td><span class="badge badge-disabled">Disabled</span></td>
                                <td>Monthly</td>
                                <td>25 Mar 2025</td>
                                <td class="text-end notification-actions">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewNotificationModal6">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editNotificationModal6">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Global Notification Preferences -->
            <div class="card p-4 mt-4">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-cog me-2" style="color: var(--primary-color)"></i>Global Notification Preferences
                </h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Delivery Channels</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                    <label class="form-check-label" for="emailNotifications">
                                        <i class="fas fa-envelope me-2"></i>Email Notifications
                                    </label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                    <label class="form-check-label" for="pushNotifications">
                                        <i class="fas fa-mobile-alt me-2"></i>Push Notifications
                                    </label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="smsNotifications">
                                    <label class="form-check-label" for="smsNotifications">
                                        <i class="fas fa-sms me-2"></i>SMS Notifications
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="inAppNotifications" checked>
                                    <label class="form-check-label" for="inAppNotifications">
                                        <i class="fas fa-bell me-2"></i>In-App Notifications
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Time Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="quietHoursStart" class="form-label">Quiet Hours Start</label>
                                    <select class="form-select" id="quietHoursStart">
                                        <option value="none">No quiet hours</option>
                                        <option value="20:00">8:00 PM</option>
                                        <option value="21:00">9:00 PM</option>
                                        <option value="22:00" selected>10:00 PM</option>
                                        <option value="23:00">11:00 PM</option>
                                        <option value="00:00">12:00 AM</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="quietHoursEnd" class="form-label">Quiet Hours End</label>
                                    <select class="form-select" id="quietHoursEnd">
                                        <option value="none">No quiet hours</option>
                                        <option value="05:00">5:00 AM</option>
                                        <option value="06:00">6:00 AM</option>
                                        <option value="07:00" selected>7:00 AM</option>
                                        <option value="08:00">8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                    </select>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="doNotDisturb">
                                    <label class="form-check-label" for="doNotDisturb">
                                        <i class="fas fa-moon me-2"></i>Do Not Disturb Mode
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-secondary me-2">Cancel</button>
                    <button class="btn btn-primary">Save Preferences</button>
                </div>
            </div>
        </div>
    </x-app-layout>

    <!-- Add Notification Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNotificationModalLabel">Add Custom Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addNotificationForm">
                        <div class="mb-3">
                            <label for="notificationType" class="form-label">Notification Type</label>
                            <input type="text" class="form-control" id="notificationType" placeholder="Enter notification type">
                        </div>
                        <div class="mb-3">
                            <label for="notificationDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="notificationDescription" rows="3" placeholder="Describe the notification"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notification Channels</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="email" id="emailChannel" checked>
                                    <label class="form-check-label" for="emailChannel">
                                        Email
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="push" id="pushChannel">
                                    <label class="form-check-label" for="pushChannel">
                                        Push
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="sms" id="smsChannel">
                                    <label class="form-check-label" for="smsChannel">
                                        SMS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="in-app" id="inAppChannel">
                                    <label class="form-check-label" for="inAppChannel">
                                        In-App
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notificationFrequency" class="form-label">Frequency</label>
                            <select class="form-select" id="notificationFrequency">
                                <option value="immediate" selected>Immediate</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notificationStatus" class="form-label">Status</label>
                            <select class="form-select" id="notificationStatus">
                                <option value="enabled" selected>Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Notification</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Notification Modal Template (would be duplicated for each notification) -->
    <div class="modal fade" id="editNotificationModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Notification - Account Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="editNotificationType" class="form-label">Notification Type</label>
                            <input type="text" class="form-control" value="Account Login" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editNotificationDescription" class="form-label">Description</label>
                            <textarea class="form-control" rows="3">Notify when a new login is detected on your account</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notification Channels</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Email</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Push</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">SMS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">In-App</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Frequency</label>
                            <select class="form-select">
                                <option value="immediate" selected>Immediate</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="enabled" selected>Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Notification Modal Template -->
    <div class="modal fade" id="viewNotificationModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account Login Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Description</h6>
                        <p>Notify when a new login is detected on your account</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Channels</h6>
                        <p>
                            <span class="badge bg-primary">Email</span>
                            <span class="badge bg-primary">Push</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Status</h6>
                        <p><span class="badge badge-enabled">Enabled</span></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Frequency</h6>
                        <p>Immediate</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Last Updated</h6>
                        <p>12 Apr 2025</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editNotificationModal1">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and Alpine JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>

</body>
</html>


