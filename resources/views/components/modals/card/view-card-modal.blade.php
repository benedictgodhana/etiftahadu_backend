<!-- resources/views/components/modals/view-card-modal.blade.php -->
@foreach ($cards as $card)
<div class="modal fade" id="viewCardModal{{ $card->id }}" tabindex="-1" aria-labelledby="viewCardModalLabel{{ $card->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCardModalLabel{{ $card->id }}">
                    <i class="fas fa-id-card me-2" style="color: var(--primary-color)"></i>Card Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row">
                    <!-- Left Column: Personal & Card Info -->
                    <div class="col-md-4">
                        <div class="d-flex flex-column h-100">
                            <!-- Personal Information -->
                            <div class="card mb-2">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                                </div>
                                <div class="card-body py-2">
                                    <p class="mb-1"><strong>Name:</strong> {{ $card->name }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $card->email }}</p>
                                    <p class="mb-1"><strong>Telephone:</strong> {{ $card->tel }}</p>
                                </div>
                            </div>

                            <!-- Card Information -->
                            <div class="card mb-2">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Card Information</h6>
                                </div>
                                <div class="card-body py-2">
                                    <p class="mb-1"><strong>Serial Number:</strong> {{ $card->serial_number }}</p>
                                    <p class="mb-1"><strong>Status:</strong>
                                        <span class="badge
                                            @if($card->status == 'active') badge-active
                                            @elseif($card->status == 'inactive') badge-inactive
                                            @else badge-suspended @endif">
                                            {{ ucfirst($card->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-1"><strong>Created By:</strong> {{ $card->user->name ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Created At:</strong> {{ $card->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Column: Card Preview -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Card Preview</h6>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div style="width: 100%; max-width: 350px;">
                                    <!-- OSONA EXPRESS Bus Smart Card Design -->
                                    <div class="card border-0" style="border-radius: 12px; overflow: hidden;">
                                        <!-- Navy blue top section with diagonal cut -->
                                        <div style="background-color: #1a237e; color: white; padding: 15px; position: relative; overflow: hidden;">
                                            <div style="position: absolute; top: 0; right: 0; width: 40%; height: 100%; background-color: #f44336; transform: skewX(-20deg); transform-origin: top right; z-index: 0;"></div>

                                            <div style="position: relative; z-index: 1;">
                                                <h4 class="mb-1" style="font-weight: bold; letter-spacing: 1px;">{{ strtoupper($card->name) }}</h4>
                                                <div style="background-color: rgba(255,255,255,0.2); border-radius: 20px; display: inline-block; padding: 3px 15px; margin-top: 5px;">
                                                    <span style="font-weight: 500; font-size: 14px;">BUS SMART CARD</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Light section with card details -->
                                        <div style="background-color: #f5f5f5; padding: 15px; position: relative;">
                                            <!-- Brand logos -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div style="width: 45%; display: flex; justify-content: space-between;">
                                                    <div style="background-color: rgba(0,0,0,0.05); border-radius: 5px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-tree" style="color: #1a237e;"></i>
                                                    </div>
                                                    <div style="background-color: #f44336; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-bus" style="color: white;"></i>
                                                    </div>
                                                </div>

                                                <!-- Chip area -->
                                                <div style="width: 60px; height: 40px; background-color: #e0e0e0; border-radius: 6px;"></div>
                                            </div>

                                            <!-- Card Information -->
                                            <div class="mt-2" style="font-size: 13px;">
                                                <div class="row mb-1">
                                                    <div class="col-4 text-muted">Email:</div>
                                                    <div class="col-8">{{ $card->email }}</div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-4 text-muted">Phone:</div>
                                                    <div class="col-8">{{ $card->tel }}</div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-4 text-muted">Serial #:</div>
                                                    <div class="col-8">{{ $card->serial_number }}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-4 text-muted">Status:</div>
                                                    <div class="col-8">
                                                        <span class="badge rounded-pill" style="background-color: {{ $card->status == 'active' ? '#22c55e' : ($card->status == 'inactive' ? '#9333ea' : '#ef4444') }}">
                                                            {{ ucfirst($card->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Issue date -->
                                            <div class="text-center mt-2" style="font-size: 11px; color: #666;">
                                                Issued: {{ $card->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Activity History -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Activity History</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Action</th>
                                                <th>By User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $card->created_at->format('M d, Y H:i') }}</td>
                                                <td>Card Created</td>
                                                <td>{{ $card->user->name ?? 'System' }}</td>
                                            </tr>
                                            @if($card->updated_at->gt($card->created_at))
                                            <tr>
                                                <td>{{ $card->updated_at->format('M d, Y H:i') }}</td>
                                                <td>Card Updated</td>
                                                <td>{{ $card->user->name ?? 'System' }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCardModal{{ $card->id }}" onclick="document.getElementById('viewCardModal{{ $card->id }}').classList.remove('show')">
                    <i class="fas fa-edit me-1"></i> Edit Card
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
