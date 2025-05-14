<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Management</title>
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

        .card-preview {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background-color: #f8f9fa;
            position: relative;
        }

        .card-preview .serial-number {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
            color: #666;
        }

        .card-detail-row {
            margin-bottom: 8px;
        }

        .card-actions {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
    <x-app-layout>
        <div class="container-fluid py-4" x-data="{
            searchQuery: '',
            cards: [],
            filteredCards: [],
            init() {
                this.cards = Array.from(document.querySelectorAll('#cardTableBody tr'))
                    .map(row => {
                        return {
                            element: row,
                            name: row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase(),
                            email: row.querySelector('td:nth-child(2)').textContent.trim().toLowerCase(),
                            serial: row.querySelector('td:nth-child(5)').textContent.trim().toLowerCase(),
                            status: row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase()
                        };
                    });

                this.filterCards();
            },
            filterCards() {
                const query = this.searchQuery.toLowerCase();
                this.filteredCards = this.cards.filter(card =>
                    card.name.includes(query) ||
                    card.email.includes(query) ||
                    card.serial.includes(query) ||
                    card.status.includes(query)
                );

                this.cards.forEach(card => {
                    if (this.filteredCards.includes(card)) {
                        card.element.classList.remove('d-none');
                    } else {
                        card.element.classList.add('d-none');
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
                        <i class="fas fa-id-card me-2" style="color: var(--primary-color)"></i>Card Management
                    </h1>
                    @can('register cards')

                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addCardModal">
                        <i class="fas fa-plus me-1"></i> Add New Card
                    </button>
                    @endcan

                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5><i class="fas fa-search me-2"></i>Search & Filter Cards</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search by name, email, or serial number..."
                                    x-model="searchQuery" @keyup="filterCards()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" @change="filterCards()" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" @click="searchQuery = ''; filterCards();">
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
                                <th>Telephone</th>
                                <th>Status</th>
                                <th>Serial Number</th>
                                <th>Issued By</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cardTableBody">
                            @foreach ($cards as $card)
                            <tr data-id="{{ $card->id }}">
                                <td>{{ $card->name }}</td>
                                <td>{{ $card->email }}</td>
                                <td>{{ $card->tel }}</td>
                                <td>
                                    <span class="badge
                                        @if($card->status == 'Active') badge-active
                                        @elseif($card->status == 'Inactive') badge-inactive
                                        @else badge-suspended @endif">
                                        {{ ucfirst($card->status) }}
                                    </span>
                                </td>
                                <td>{{ $card->serial_number }}</td>
                                <td>{{ $card->user->name ?? 'N/A' }}</td>
                                <td>{{ $card->created_at->format('Y-m-d') }}</td>
                                <td>
    <div class="card-actions">

        @can('view cards')
        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
            data-bs-target="#viewCardModal{{ $card->id }}">
            <i class="fas fa-eye"></i>
        </button>
        @endcan

        @can('edit cards')
        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
            data-bs-target="#editCardModal{{ $card->id }}">
            <i class="fas fa-edit"></i>
        </button>
        @endcan

        @can('top-up cards')
        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
            data-bs-target="#topUpCardModal{{ $card->id }}">
            <i class="fas fa-wallet"></i>
        </button>
        @endcan

        @can('block cards')
        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
            data-bs-target="#blockCardModal{{ $card->id }}">
            <i class="fas fa-ban"></i>
        </button>
        @endcan

        @can('unblock cards')
        <button class="btn btn-sm btn-light" data-bs-toggle="modal"
            data-bs-target="#unblockCardModal{{ $card->id }}">
            <i class="fas fa-unlock"></i>
        </button>
        @endcan

        @can('transfer cards')
        <button class="btn btn-sm btn-dark" data-bs-toggle="modal"
            data-bs-target="#transferCardModal{{ $card->id }}">
            <i class="fas fa-random"></i>
        </button>
        @endcan

        @can('delete cards')
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
            data-bs-target="#deleteCardModal{{ $card->id }}">
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
                    {{ $cards->links() }}
                </div>
            </div>
        </div>
    </x-app-layout>
<!-- Include modals from external files -->
@include('components.modals.card.add-card-modal')
@include('components.modals.card.edit-card-modal')
@include('components.modals.card.delete-card-modal')
@include('components.modals.card.view-card-modal')
@include('components.modals.card.topup-card-modal')
@include('components.modals.card.block-card-modal')
@include('components.modals.card.unblock-card-modal')
@include('components.modals.card.transfer-card-modal')

    <!-- Bootstrap and Alpine JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script>
        // Card status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#cardTableBody tr');

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
