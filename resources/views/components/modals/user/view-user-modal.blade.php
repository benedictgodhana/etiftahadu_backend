
<!-- View User Modal -->
@foreach ($users as $user)

<div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="viewUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="viewUserModalLabel{{ $user->id }}">
                    <i class="fas fa-user text-info me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="user-preview mb-4">
                    <div class="user-id"># {{ $user->id }}</div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Name:</div>
                        <div class="col-md-9">{{ $user->name }}</div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">User Name:</div>
                        <div class="col-md-9">{{ $user->username }}</div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Email:</div>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Role:</div>
                        <div class="col-md-9">  @foreach ($user->roles as $role)
        {{ $role->name }}@if (!$loop->last), @endif
    @endforeach</div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Status:</div>
                        <div class="col-md-9">
                            <span class="badge
                                @if($user->status == 'Active') badge-active
                                @elseif($user->status == 'Inactive') badge-inactive
                                @else badge-suspended @endif">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Phone:</div>
                        <div class="col-md-9">{{ $user->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Last Login:</div>
                        <div class="col-md-9">{{ $user->last_login ? $user->last_login->format('Y-m-d H:i:s') : 'Never' }}</div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Created:</div>
                        <div class="col-md-9">{{ $user->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="row user-detail-row">
                        <div class="col-md-3 fw-bold">Last Updated:</div>
                        <div class="col-md-9">{{ $user->updated_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Permissions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($user->getAllPermissions()->chunk(4) as $chunk)
                                @foreach($chunk as $permission)
                                    <div class="col-md-3 mb-2">
                                        <span class="badge bg-secondary">{{ $permission->name }}</span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endforeach
