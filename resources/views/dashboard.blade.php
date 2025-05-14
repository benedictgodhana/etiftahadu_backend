<x-app-layout>
    <!-- Dashboard Content -->
    <div class="dashboard-container p-6">
        <h1 class="page-title text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-home mr-2"></i> Dashboard Overview
        </h1>

        <!-- Metrics Overview -->
        <div class="metrics-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Bookings -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-ticket-alt text-blue-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">{{ $totalBookings ?? 0 }}</div>
                        <div class="metric-label text-sm text-gray-500">Total Bookings</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-green-600">
                    <i class="fas fa-arrow-up"></i> 12% from last month
                </div>
            </div>

            <!-- Registered Users -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-green-100 p-3 rounded-full mr-4">
                        <i class="fas fa-user-plus text-green-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">{{ $registeredUsers }}</div>
                        <div class="metric-label text-sm text-gray-500">Registered Users</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-green-600">
                    <i class="fas fa-arrow-up"></i> 5% from last month
                </div>
            </div>

            <!-- Total Card Holders -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-purple-100 p-3 rounded-full mr-4">
                        <i class="fas fa-id-card text-purple-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">{{ $totalCardHolders }}</div>
                        <div class="metric-label text-sm text-gray-500">Total Card Holders</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-green-600">
                    <i class="fas fa-arrow-up"></i> 8% from last month
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-yellow-100 p-3 rounded-full mr-4">
                        <i class="fas fa-dollar-sign text-yellow-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">${{ number_format($monthlyRevenue, 2) }}</div>
                        <div class="metric-label text-sm text-gray-500">Monthly Revenue</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-green-600">
                    <i class="fas fa-arrow-up"></i> 15% from last month
                </div>
            </div>

            <!-- Today Revenue -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-red-100 p-3 rounded-full mr-4">
                        <i class="fas fa-hand-holding-usd text-red-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">${{ number_format($todayRevenue, 2) }}</div>
                        <div class="metric-label text-sm text-gray-500">Today's Revenue</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-green-600">
                    <i class="fas fa-arrow-up"></i> 3% from yesterday
                </div>
            </div>

            <!-- Top-Up Transactions -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-indigo-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-indigo-100 p-3 rounded-full mr-4">
                        <i class="fas fa-money-bill-wave text-indigo-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">{{ $topUpTransactions }}</div>
                        <div class="metric-label text-sm text-gray-500">Top-Up Transactions</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-green-600">
                    <i class="fas fa-arrow-up"></i> 7% from last week
                </div>
            </div>

            <!-- Available Bus Routes -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-teal-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-teal-100 p-3 rounded-full mr-4">
                        <i class="fas fa-route text-teal-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">{{ $availableBusRoutes }}</div>
                        <div class="metric-label text-sm text-gray-500">Available Bus Routes</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-600">
                    <i class="fas fa-minus"></i> No change
                </div>
            </div>

            <!-- Active Buses -->
            <div class="metric-card bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg border-l-4 border-pink-500">
                <div class="flex items-center">
                    <div class="metric-icon bg-pink-100 p-3 rounded-full mr-4">
                        <i class="fas fa-bus text-pink-500 text-xl"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value text-2xl font-bold text-gray-800">{{ $buses ?? 42 }}</div>
                        <div class="metric-label text-sm text-gray-500">Active Buses</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-red-600">
                    <i class="fas fa-arrow-down"></i> 2% from yesterday
                </div>
            </div>
        </div>

     
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Trend Chart
            const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: [12500, 15000, 17500, 16800, 19200, 21000, 22400, 24300, 25600, 27800, 28900, 30200],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Route Chart
            const routeCtx = document.getElementById('route-chart').getContext('2d');
            const routeChart = new Chart(routeCtx, {
                type: 'bar',
                data: {
                    labels: ['Route #1', 'Route #2', 'Route #3', 'Route #4', 'Route #5', 'Route #6'],
                    datasets: [{
                        label: 'Bookings by Route',
                        data: [125, 87, 143, 99, 112, 78],
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(107, 114, 128, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // User Registration Chart
            const userCtx = document.getElementById('user-chart').getContext('2d');
            const userChart = new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'New Users',
                        data: [65, 78, 90, 115, 130, 142],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Transaction Types Chart
            const transactionCtx = document.getElementById('transaction-chart').getContext('2d');
            const transactionChart = new Chart(transactionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Bookings', 'Top-Ups', 'Card Issuance', 'Refunds'],
                    datasets: [{
                        data: [65, 25, 8, 2],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
