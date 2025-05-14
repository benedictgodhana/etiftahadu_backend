<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Namib</title>
    <style>
        :root {
            --primary-color: red; /* Orangered */
            --primary-hover: white; /* Darker shade for hover states */
            --primary-light: #ffe0d5; /* Light shade for backgrounds */
            --text-on-primary: #ffffff; /* White text on primary color */
            --text-dark: #1f2937; /* Dark text for content */
            --background-light: #f9fafb; /* Light background */
            --background-white: #ffffff; /* White background */
            --border-color: #e5e7eb; /* Border color */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --header-height: 64px;
            --transition-speed: 0.3s;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @font-face {
            font-family: 'Futura LT';
            src: url('/fonts/futura-lt/FuturaLT-Book.ttf') format('woff2'),
                 url('/fonts/futura-lt/FuturaLT.ttf') format('woff'),
                 url('/fonts/futura-lt/FuturaLT-Condensed.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Futura LT', 'Poppins', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            transition: width var(--transition-speed) ease;
            position: fixed;
            height: 100vh;
            z-index: 50;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            height: var(--header-height);
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-on-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .nav-items {
            padding: 1rem 0.75rem;
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-on-primary);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all var(--transition-speed) ease;
            white-space: nowrap;
        }

        .nav-link:hover {
            background-color: var(--primary-hover);
        }

        .nav-link.active {
            background-color: var(--background-white);
            color: var(--primary-color);
            font-weight: 600;
        }

        .nav-icon {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--header-height);
            background-color: var(--background-white);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: left var(--transition-speed) ease;
            z-index: 40;
            box-shadow: var(--shadow-sm);
        }

        .sidebar.collapsed ~ .header {
            left: var(--sidebar-collapsed-width);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--text-dark);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-btn:hover {
            background-color: var(--background-light);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: var(--text-dark);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background-color: var(--primary-color);
            color: var(--text-on-primary);
            font-size: 0.75rem;
            padding: 0.125rem 0.375rem;
            border-radius: 1rem;
            min-width: 1.5rem;
            text-align: center;
        }

        .user-menu-wrapper {
            position: relative;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
        }

        .user-menu:hover {
            background-color: var(--background-light);
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            background-color: var(--primary-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background: var(--background-white);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            border-radius: 0.5rem;
            min-width: 180px;
            z-index: 1000;
            overflow: hidden;
        }

        .dropdown-menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .dropdown-menu ul li {
            padding: 0;
        }

        .dropdown-menu ul li a {
            text-decoration: none;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all var(--transition-speed) ease;
        }

        .dropdown-menu ul li a:hover {
            background: var(--background-light);
            color: var(--primary-color);
        }

        .dropdown-menu.show {
            display: block;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            transition: margin-left var(--transition-speed) ease;
            width: calc(100% - var(--sidebar-width));
            overflow-x: hidden;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Dashboard Cards & Components */
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background-color: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .metric-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-light);
            border-radius: 0.5rem;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .metric-content {
            flex: 1;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .metric-label {
            font-size: 0.875rem;
            color: var(--text-dark);
        }

        .card {
            background-color: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .card-action {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            background-color: var(--background-light);
            color: var(--text-dark);
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: var(--background-light);
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #10b981;
            color: white;
        }

        .badge-warning {
            background-color: #f59e0b;
            color: white;
        }

        .badge-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--text-on-primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
        }

        .btn-outline:hover {
            background-color: var(--background-light);
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .metrics-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.collapsed {
                transform: translateX(0);
                width: var(--sidebar-collapsed-width);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .header {
                left: 0;
            }

            .sidebar.collapsed ~ .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar.collapsed ~ .main-content {
                margin-left: var(--sidebar-collapsed-width);
                width: calc(100% - var(--sidebar-collapsed-width));
            }
        }

        @media (max-width: 640px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="layout" x-data="{ sidebarOpen: true, userDropdownOpen: false }">
        <!-- Sidebar -->
        <aside class="sidebar" :class="{'collapsed': !sidebarOpen, 'mobile-open': sidebarOpen}" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-bus-alt mr-2"></i>
                    <span class="logo-text">NAMIB</span>
                </div>
            </div>

            <nav class="nav-items">
    <!-- Dashboard -->
    @can('view dashboard')
    <div class="nav-item">
        <a href="dashboard" class="nav-link active">
            <i class="fas fa-home nav-icon"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </div>
    @endcan

    <!-- Commute Schedules -->
    @can('view schedules')
    <div class="nav-item">
        <a href="commute-schedules" class="nav-link">
            <i class="fas fa-calendar-alt nav-icon"></i>
            <span class="nav-text">Commute Schedules</span>
        </a>
    </div>
    @endcan
<!-- Manage Buses -->
@can('manage buses')
<div class="nav-item">
    <a href="buses" class="nav-link">
        <i class="fas fa-bus nav-icon"></i>
        <span class="nav-text">Manage Buses</span>
    </a>
</div>
@endcan

    <!-- Routes -->
    @can('view routes')
    <div class="nav-item">
        <a href="all-routes" class="nav-link">
            <i class="fas fa-route nav-icon"></i>
            <span class="nav-text">Routes</span>
        </a>
    </div>
    @endcan

    <!-- Manage Users -->
    @can('view users')
    <div class="nav-item">
        <a href="users" class="nav-link">
            <i class="fas fa-users nav-icon"></i>
            <span class="nav-text">Manage Users</span>
        </a>
    </div>
    @endcan

    <!-- Cards -->
    @can('view cards')
    <div class="nav-item">
        <a href="cards" class="nav-link">
            <i class="fas fa-credit-card nav-icon"></i>
            <span class="nav-text">Cards</span>
        </a>
    </div>
    @endcan

    <!-- Card Top Up -->
    @can('top-up cards')
    <div class="nav-item">
        <a href="all-card-topups" class="nav-link">
            <i class="fas fa-coins nav-icon"></i>
            <span class="nav-text">Card Top Up</span>
        </a>
    </div>
    @endcan

    <!-- Offers -->
    @can('view offers')
    <div class="nav-item">
        <a href="all-offers" class="nav-link">
            <i class="fas fa-tag nav-icon"></i>
            <span class="nav-text">Offers</span>
        </a>
    </div>
    @endcan

    <!-- Transactions -->
    @can('view transactions')
    <div class="nav-item">
        <a href="ticket-transactions" class="nav-link">
            <i class="fas fa-exchange-alt nav-icon"></i>
            <span class="nav-text">Ticket Transactions</span>
        </a>
    </div>
    @endcan

    <!-- Reports & Analytics -->
    @can('view reports')
    <div class="nav-item">
        <a href="reports" class="nav-link">
            <i class="fas fa-chart-line nav-icon"></i>
            <span class="nav-text">Reports & Analytics</span>
        </a>
    </div>
    @endcan

    <!-- System Settings -->
    @can('access admin panel')
    <div class="nav-item">
        <a href="system-management" class="nav-link">
            <i class="fas fa-cogs nav-icon"></i>
            <span class="nav-text">System Settings</span>
        </a>
    </div>
    @endcan

    <!-- Support -->
    @can('handle support tickets')
    <div class="nav-item">
        <a href="support" class="nav-link">
            <i class="fas fa-headset nav-icon"></i>
            <span class="nav-text">Support</span>
        </a>
    </div>
    @endcan

    <!-- Profile -->
    <div class="nav-item">
        <a href="profile" class="nav-link">
            <i class="fas fa-user-circle nav-icon"></i>
            <span class="nav-text">Profile</span>
        </a>
    </div>

    <!-- Logout -->
    <div class="nav-item">
        <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt nav-icon"></i>
            <span class="nav-text">Logout</span>
        </a>
        <form id="logout-form" action="/logout" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</nav>

        </aside>

        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-info">
                    <span class="font-medium">Dashboard</span>
                </div>
            </div>
            <div class="header-right">
                <!-- Notification Button -->
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>

                <!-- User Menu -->
                <div class="user-menu-wrapper">
                    <div class="user-menu" @click="userDropdownOpen = !userDropdownOpen">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <span>John Doe</span>
                        <i class="fas fa-chevron-down ml-1 text-sm"></i>
                    </div>
                    <div class="dropdown-menu" :class="{'show': userDropdownOpen}" @click.away="userDropdownOpen = false">
                        <ul>
                            <li><a href="profile"><i class="fas fa-user-circle"></i> My Profile</a></li>
                            <li><a href="settings"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><a href="#" onclick="document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            {{ $slot }}
        </main>
    </div>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        // Handle responsive behavior
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 768) {
                sidebar.classList.add('collapsed');
            }
        });

        // Initialize current page highlight
        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.pathname.split('/').pop() || 'dashboard';

            document.querySelectorAll(".nav-link").forEach(link => {
                const linkPath = link.getAttribute("href");
                if (linkPath === currentPath) {
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });
        });
    </script>
</body>
</html>
