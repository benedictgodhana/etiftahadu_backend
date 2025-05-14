<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
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

        .settings-nav {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .settings-nav .nav-link {
            color: var(--text-color);
            padding: 12px 20px;
            border-radius: 0;
            border-left: 3px solid transparent;
        }

        .settings-nav .nav-link:hover {
            background-color: #f8f9fa;
        }

        .settings-nav .nav-link.active {
            background-color: #f0f4ff;
            border-left: 3px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }

        .settings-nav .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 8px;
        }

        .settings-card {
            display: none;
        }

        .settings-card.active {
            display: block;
        }

        .settings-title {
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
        }

        .role-actions, .user-actions {
            display: flex;
            gap: 8px;
        }

        .setting-actions button {
            margin-right: 10px;
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

        /* Toggle Switch Styles */
        .form-check-input.switch {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .settings-nav {
                margin-bottom: 20px;
            }
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
        <div class="container-fluid py-4">
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

            <!-- Header -->
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center page-header">
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-cogs me-2" style="color: var(--primary-color)"></i>Admin Settings
                    </h1>
                </div>

                <div class="row">
                    <!-- Navigation -->
                    <div class="col-md-3">
                        <div class="settings-nav">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                                    <i class="fas fa-sliders-h"></i> General Settings
                                </button>

                                <button class="nav-link" id="emails-tab" data-bs-toggle="pill" data-bs-target="#emails" type="button" role="tab">
                                    <i class="fas fa-envelope"></i> Email Settings
                                </button>
                                <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                                    <i class="fas fa-shield-alt"></i> Security
                                </button>
                                <button class="nav-link" id="appearance-tab" data-bs-toggle="pill" data-bs-target="#appearance" type="button" role="tab">
                                    <i class="fas fa-paint-brush"></i> Appearance
                                </button>
                                <button class="nav-link" id="backups-tab" data-bs-toggle="pill" data-bs-target="#backups" type="button" role="tab">
                                    <i class="fas fa-database"></i> Backups & Logs
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="col-md-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="card p-4">
                                    <h3 class="settings-title"><i class="fas fa-sliders-h me-2"></i>General Settings</h3>

                                    <form>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Site Name</label>
                                                    <input type="text" class="form-control" value="My Application">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Contact Email</label>
                                                    <input type="email" class="form-control" value="admin@example.com">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Timezone</label>
                                                    <select class="form-select">
                                                        <option>UTC</option>
                                                        <option selected>America/New_York</option>
                                                        <option>Europe/London</option>
                                                        <option>Asia/Tokyo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Date Format</label>
                                                    <select class="form-select">
                                                        <option>YYYY-MM-DD</option>
                                                        <option selected>MM/DD/YYYY</option>
                                                        <option>DD/MM/YYYY</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Site Description</label>
                                                    <textarea class="form-control" rows="3">This is my application's description that appears in search engines and social media.</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="enableRegistration" checked>
                                                    <label class="form-check-label" for="enableRegistration">Enable User Registration</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="maintenanceMode">
                                                    <label class="form-check-label" for="maintenanceMode">Maintenance Mode</label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                                    </form>
                                </div>
                            </div>

                         
                            <!-- Email Settings -->
                            <div class="tab-pane fade" id="emails" role="tabpanel" aria-labelledby="emails-tab">
                                <div class="card p-4">
                                    <h3 class="settings-title"><i class="fas fa-envelope me-2"></i>Email Settings</h3>

                                    <form>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Mail Driver</label>
                                                    <select class="form-select">
                                                        <option>SMTP</option>
                                                        <option>Mailgun</option>
                                                        <option>Postmark</option>
                                                        <option>SES</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Mail Host</label>
                                                    <input type="text" class="form-control" value="smtp.mailtrap.io">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Mail Port</label>
                                                    <input type="text" class="form-control" value="2525">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Mail Encryption</label>
                                                    <select class="form-select">
                                                        <option>None</option>
                                                        <option selected>TLS</option>
                                                        <option>SSL</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Mail Username</label>
                                                    <input type="text" class="form-control" value="7a8b5c1d2e3f">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Mail Password</label>
                                                    <input type="password" class="form-control" value="************">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">From Address</label>
                                                    <input type="email" class="form-control" value="no-reply@example.com">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">From Name</label>
                                                    <input type="text" class="form-control" value="My Application">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="sendWelcomeEmail" checked>
                                                    <label class="form-check-label" for="sendWelcomeEmail">Send Welcome Email to New Users</label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Save Email Settings</button>
                                        <button type="button" class="btn btn-secondary mt-3 ms-2">Test Email Configuration</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Security -->
                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <div class="card p-4">
                                    <h3 class="settings-title"><i class="fas fa-shield-alt me-2"></i>Security Settings</h3>

                                    <form>
                                        <h5 class="mt-3 mb-3">Authentication</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="enableTwoFactor" checked>
                                                    <label class="form-check-label" for="enableTwoFactor">Enable Two-Factor Authentication</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="enableRecaptcha" checked>
                                                    <label class="form-check-label" for="enableRecaptcha">Enable reCAPTCHA</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Password Minimum Length</label>
                                                    <input type="number" class="form-control" value="8">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Password Expiry (Days)</label>
                                                    <input type="number" class="form-control" value="90">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="requireSpecialChar" checked>
                                                    <label class="form-check-label" for="requireSpecialChar">Require Special Characters</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="requireUppercase" checked>
                                                    <label class="form-check-label" for="requireUppercase">Require Uppercase Letters</label>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mt-4 mb-3">Session Management</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Session Timeout (Minutes)</label>
                                                    <input type="number" class="form-control" value="30">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="rememberMeEnabled" checked>
                                                    <label class="form-check-label" for="rememberMeEnabled">Enable "Remember Me" Option</label>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mt-4 mb-3">Throttling & Protection</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Max Login Attempts</label>
                                                    <input type="number" class="form-control" value="5">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Lockout Duration (Minutes)</label>
                                                    <input type="number" class="form-control" value="15">
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-4">Save Security Settings</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Appearance -->
                            <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                                <div class="card p-4">
                                    <h3 class="settings-title"><i class="fas fa-paint-brush me-2"></i>Appearance Settings</h3>

                                    <form>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Theme</label>
                                                    <select class="form-select">
                                                        <option selected>Light</option>
                                                        <option>Dark</option>
                                                        <option>System Default</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Primary Color</label>
                                                    <input type="color" class="form-control form-control-color" value="#4F46E5">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Logo</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" id="logoUpload">
                                                        <button class="btn btn-outline-secondary" type="button">Upload</button>
                                                    </div>
                                                    <div class="mt-2">
                                                        <small class="form-text text-muted">Recommended size: 200x50px, PNG or SVG</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Favicon</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" id="faviconUpload">
                                                        <button class="btn btn-outline-secondary" type="button">Upload</button>
                                                    </div>
                                                    <div class="mt-2">
                                                        <small class="form-text text-muted">Recommended size: 32x32px, ICO or PNG</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="showLogo" checked>
                                                    <label class="form-check-label" for="showLogo">Show Logo in Navigation</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-check form-switch">
                                                    <input class="form-check-input switch" type="checkbox" id="stickyHeader" checked>
                                                    <label class="form-check-label" for="stickyHeader">Sticky Header</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Custom CSS</label>
                                                    <textarea class="form-control" rows="5" placeholder="/* Add your custom CSS here */"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-3">Save Appearance Settings</button>
                                        <button type="button" class="btn btn-secondary mt-3 ms-2">Reset to Default</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Backups & Logs -->
                            <div class="tab-pane fade" id="backups" role="tabpanel" aria-labelledby="backups-tab">
                                <div class="card p-4">
                                    <h3 class="settings-title"><i class="fas fa-database me-2"></i>Backups & Logs</h3>

                                    <!-- Backups Section -->
                                    <div class="mb-5">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5>Database Backups</h5>
                                            <button class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Create New Backup
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Filename</th>
                                                        <th>Size</th>
                                                        <th>Created At</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>backup_2023_03_28.sql</td>
                                                        <td>24.5 MB</td>
                                                        <td>2023-03-28 08:30</td>
                                                        <td class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-primary">
                                                                <i class="fas fa-download"></i> Download
                                                            </button>
                                                            <button class="btn btn-sm btn-success">
                                                                <i class="fas fa-redo"></i> Restore
                                                            </button>
                                                            <button class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>backup_2023_03_21.sql</td>
                                                        <td>24.2 MB</td>
                                                        <td>2023-03-21 08:30</td>
                                                        <td class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-primary">
                                                                <i class="fas fa-download"></i> Download
                                                            </button>
                                                            <button class="btn btn-sm btn-success">
                                                                <i class="fas fa-redo"></i> Restore
                                                            </button>
                                                            <button class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>backup_2023_03_14.sql</td>
                                                        <td>23.8 MB</td>
                                                        <td>2023-03-14 08:30</td>
                                                        <td class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-primary">
                                                                <i class="fas fa-download"></i> Download
                                                            </button>
                                                            <button class="btn btn-sm btn-success">
                                                                <i class="fas fa-redo"></i> Restore
                                                            </button>
                                                            <button class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-3">
                                            <div class="form-group form-check form-switch">
                                                <input class="form-check-input switch" type="checkbox" id="automaticBackup" checked>
                                                <label class="form-check-label" for="automaticBackup">Enable Automatic Backups</label>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-4">
                                                    <select class="form-select">
                                                        <option>Daily</option>
                                                        <option selected>Weekly</option>
                                                        <option>Monthly</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-text">Next automatic backup scheduled for: April 20, 2025</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- System Logs Section -->
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5>System Logs</h5>
                                            <div>
                                                <button class="btn btn-secondary me-2">
                                                    <i class="fas fa-file-download me-1"></i> Download All Logs
                                                </button>
                                                <button class="btn btn-danger">
                                                    <i class="fas fa-trash me-1"></i> Clear Logs
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Message</th>
                                                        <th>User</th>
                                                        <th>IP Address</th>
                                                        <th>Timestamp</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><span class="badge bg-info">INFO</span></td>
                                                        <td>User login successful</td>
                                                        <td>john.doe@example.com</td>
                                                        <td>192.168.1.100</td>
                                                        <td>2023-03-28 14:35:22</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-warning">WARNING</span></td>
                                                        <td>Failed login attempt</td>
                                                        <td>unknown@example.com</td>
                                                        <td>192.168.1.105</td>
                                                        <td>2023-03-28 13:22:15</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-danger">ERROR</span></td>
                                                        <td>Database connection failed</td>
                                                        <td>System</td>
                                                        <td>127.0.0.1</td>
                                                        <td>2023-03-28 12:15:46</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-success">SUCCESS</span></td>
                                                        <td>Backup created successfully</td>
                                                        <td>System</td>
                                                        <td>127.0.0.1</td>
                                                        <td>2023-03-28 08:30:00</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-3">
                                            <div class="form-group form-check form-switch">
                                                <input class="form-check-input switch" type="checkbox" id="detailedLogging" checked>
                                                <label class="form-check-label" for="detailedLogging">Enable Detailed Logging</label>
                                            </div>
                                            <div class="form-group form-check form-switch mt-2">
                                                <input class="form-check-input switch" type="checkbox" id="notifyErrors" checked>
                                                <label class="form-check-label" for="notifyErrors">Notify Admin of Critical Errors</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
