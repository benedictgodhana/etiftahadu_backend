<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Transaction Management</title>
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

        .badge-completed {
            background-color: #C6F6D5;
            color: #22543D;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #E9D8FD;
            color: #553C9A;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }

        .badge-cancelled {
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

        .ticket-preview {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #f8f9fa;
            position: relative;
        }

        .ticket-preview .ticket-id {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
            color: #666;
        }

        .ticket-detail-row {
            margin-bottom: 8px;
        }

        .ticket-actions {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
    <x-app-layout>
        <div class="container-fluid py-4" x-data="{
            searchQuery: '',
            tickets: [],
            filteredTickets: [],
            init() {
                this.tickets = Array.from(document.querySelectorAll('#ticketTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            ticket: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            route: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            card: row.querySelector('td:nth-child(3)').textContent.trim().toLowerCase(),
                            amount: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(5)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterTickets();
            },
            filterTickets() {
                const query = this.searchQuery.toLowerCase();
                this.filteredTickets = this.tickets.filter(ticket =>
                    ticket.ticket.includes(query) ||
                    ticket.route.includes(query) ||
                    ticket.card.includes(query) ||
                    ticket.amount.includes(query) ||
                    ticket.status.includes(query)
                );

                this.tickets.forEach(ticket => {
                    if (this.filteredTickets.includes(ticket)) {
                        ticket.element.classList.remove('d-none');
                    } else {
                        ticket.element.classList.add('d-none');
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
                        <i class="fas fa-ticket-alt me-2" style="color: var(--primary-color)"></i>Ticket Transaction Management
                    </h1>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addTicketModal">
                        <i class="fas fa-plus me-1"></i> Add New Transaction
                    </button>
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Transactions</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by ticket number, route, card..."
                                    x-model="searchQuery" @keyup="filterTickets()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" @change="filterTickets()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text">Date Range</span>
                                <input type="date" class="form-control" id="startDate">
                                <input type="date" class="form-control" id="endDate">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; filterTickets();">
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
                                <th>Ticket Number</th>
                                <th>Route</th>
                                <th>Card</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Transaction Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ticketTableBody">
                            @foreach ($tickets as $ticket)
                            <tr data-id="{{ $ticket->id }}">
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ $ticket->route->from }} - {{ $ticket->route->to }}</td>
                                <td>{{ $ticket->card->name}}</td>
                                <td>${{ number_format($ticket->amount, 2) }}</td>
                                <td>
                                    @if($ticket->status == 'completed')
                                        <span class="badge badge-completed">Completed</span>
                                    @elseif($ticket->status == 'pending')
                                        <span class="badge badge-pending">Pending</span>
                                    @elseif($ticket->status == 'cancelled')
                                        <span class="badge badge-cancelled">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="ticket-actions">
                                        @can('view tickets')
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewTicketModal{{ $ticket->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @endcan

                                        @can('edit tickets')
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editTicketModal{{ $ticket->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan

                                        @can('delete tickets')
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteTicketModal{{ $ticket->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endcan

                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#printTicketModal{{ $ticket->id }}">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>



                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                {{$tickets->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            </div>
        </div>
    </x-app-layout>

    <!-- Add Ticket Modal -->
    <div class="modal fade" id="addTicketModal" tabindex="-1" aria-labelledby="addTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTicketModalLabel">Add New Ticket Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ticket_number" class="form-label">Ticket Number</label>
                                <input type="text" class="form-control" id="ticket_number" name="ticket_number" required>
                            </div>
                            <div class="col-md-6">
                                <label for="route_id" class="form-label">Route</label>
                                <select class="form-select" id="route_id" name="route_id" required>
                                    <option value="">Select Route</option>
                                    @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->from }} - {{ $route->to }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="card_id" class="form-label">Card ID</label>
                                <input type="text" class="form-control" id="card_id" name="card_id" required>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="transaction_date" class="form-label">Transaction Date</label>
                                <input type="datetime-local" class="form-control" id="transaction_date" name="transaction_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Ticket Modal Template -->
    <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ticket Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ticket-preview mb-4">
                        <div class="ticket-id">#TKT-12345</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="ticket-detail-row">
                                    <strong>Ticket Number:</strong> TKT-12345-ABC
                                </div>
                                <div class="ticket-detail-row">
                                    <strong>Route:</strong> Downtown - Airport
                                </div>
                                <div class="ticket-detail-row">
                                    <strong>Card ID:</strong> CARD-5678
                                </div>
                                <div class="ticket-detail-row">
                                    <strong>Amount:</strong> $25.00
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ticket-detail-row">
                                    <strong>Status:</strong>
                                    <span class="badge badge-completed">Completed</span>
                                </div>
                                <div class="ticket-detail-row">
                                    <strong>Transaction Date:</strong> 2025-04-12 14:30
                                </div>
                                <div class="ticket-detail-row">
                                    <strong>Created By:</strong> Admin User
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <strong>Notes:</strong>
                            <p>Round trip ticket purchase with express service included.</p>
                        </div>
                    </div>
                    <h6>Transaction History</h6>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div><i class="fas fa-plus-circle text-success me-2"></i> Transaction Created</div>
                                <div>2025-04-12 14:30</div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div><i class="fas fa-check-circle text-primary me-2"></i> Payment Processed</div>
                                <div>2025-04-12 14:31</div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div><i class="fas fa-print text-info me-2"></i> Ticket Printed</div>
                                <div>2025-04-12 14:32</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-print me-1"></i> Print Ticket
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and Alpine JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script>
        // Ticket status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#ticketTableBody tr');

            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(5)');
                const statusText = statusCell.textContent.trim().toLowerCase();

                if (statusValue === '' || statusText.includes(statusValue)) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        });

        // Date range filter functionality
        document.getElementById('startDate').addEventListener('change', applyDateFilter);
        document.getElementById('endDate').addEventListener('change', applyDateFilter);

        function applyDateFilter() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate && !endDate) return;

            const rows = document.querySelectorAll('#ticketTableBody tr');

            rows.forEach(row => {
                const dateCell = row.querySelector('td:nth-child(6)').textContent.trim();
                const transactionDate = new Date(dateCell);

                let show = true;

                if (startDate && new Date(startDate) > transactionDate) {
                    show = false;
                }

                if (endDate && new Date(endDate) < transactionDate) {
                    show = false;
                }

                if (show) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        }
    </script>
</body>
</html>
