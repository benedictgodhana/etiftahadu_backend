<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        .profile-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .profile-image-container {
            position: relative;
            display: inline-block;
        }
        .profile-image {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .edit-image-button {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #3498db;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .edit-image-button:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }
        .profile-heading {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .profile-info dt {
            color: #6c757d;
            font-weight: 600;
        }
        .profile-info dd {
            margin-bottom: 1rem;
            font-size: 1.05rem;
        }
        .btn-edit-profile {
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-edit-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .tab-pane {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <x-app-layout>
    <div class="container-fluid mt-4">
    <div class="card p-4">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a class="list-group-item list-group-item-action active" data-bs-toggle="tab" href="#account">
                        <i class="fas fa-user-circle me-2"></i>Account Settings
                    </a>
                    <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#security">
                        <i class="fas fa-shield-alt me-2"></i>Security
                    </a>
                    <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#notifications">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </a>
                    <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#social">
                        <i class="fas fa-link me-2"></i>Social Accounts
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Account Settings Tab -->
                    <div class="tab-pane fade show active" id="account">
                        <div class="row">
                            <div class="col-md-4 text-center">
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                            class="profile-image rounded-circle"
                            alt="Profile Image">

                            </div>
                            <div class="col-md-8">
                                <h4 class="mb-3">Profile Information</h4>
                                <dl class="row">
                                    <dt class="col-sm-3">Full Name</dt>
                                    <dd class="col-sm-9">{{ auth()->user()->name }}</dd>

                                    <dt class="col-sm-3">Email</dt>
                                    <dd class="col-sm-9">{{ auth()->user()->email }}</dd>

                                    <dt class="col-sm-3">Account Created</dt>
                                    <dd class="col-sm-9">{{ auth()->user()->created_at->diffForHumans() }}</dd>

                                    <dt class="col-sm-3">Last Updated</dt>
                                    <dd class="col-sm-9">{{ auth()->user()->updated_at->diffForHumans() }}</dd>
                                </dl>
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security">
                        <h4 class="mb-4">Security Settings</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Change Password</h5>
                                <form action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" name="new_password_confirmation" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </form>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Two-Factor Authentication</h5>
                                <p class="text-muted">Add an extra layer of security to your account.</p>
                                <button class="btn btn-outline-success">
                                    <i class="fas fa-mobile-alt"></i> Enable 2FA
                                </button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Active Sessions</h5>
                                <p class="text-muted">Manage devices that are logged into your account.</p>
                                <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
        {{ request()->header('User-Agent') }} <!-- Raw User-Agent -->
        <strong>{{ session('browser', 'Unknown') }} on {{ session('platform', 'Unknown') }}</strong>
        <button class="btn btn-sm btn-outline-danger">Logout</button>
    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Tab -->
                    <div class="tab-pane fade" id="notifications">
                        <h4 class="mb-4">Notification Preferences</h4>
                        <form action="#" method="POST">
                            @csrf
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Email Notifications</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="newsletter" checked>
                                        <label class="form-check-label" for="newsletter">
                                            Receive newsletter
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="promotions" checked>
                                        <label class="form-check-label" for="promotions">
                                            Special offers and promotions
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Push Notifications</h5>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                        <label class="form-check-label" for="pushNotifications">
                                            Enable push notifications
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Save Preferences</button>
                        </form>
                    </div>

                    <!-- Social Accounts Tab -->
                    <div class="tab-pane fade" id="social">
                        <h4 class="mb-4">Connected Accounts</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <i class="fab fa-google me-2"></i> Google Account
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm">Disconnect</button>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <i class="fab fa-facebook me-2"></i> Facebook Account
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm">Connect</button>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fab fa-twitter me-2"></i> Twitter Account
                                    </div>
                                    <button class="btn btn-outline-info btn-sm">Connect</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="mt-5 border-top pt-4">
                    <h4 class="text-danger mb-3"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h4>
                    <div class="card border-danger">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Delete Account</h5>
                            <p class="text-muted">Permanently delete your account and all associated data.</p>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fas fa-trash-alt"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger">Permanently Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </x-app-layout>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="profile_image" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session('success'))
                    toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
</body>
</html>
