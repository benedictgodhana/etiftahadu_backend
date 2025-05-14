<!-- Edit Role Modal - Redesigned with improved permissions organization -->
@foreach ($roles as $role)

<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">
                    <i class="fas fa-edit me-2" style="color: var(--primary-color)"></i>Edit Role: {{ $role->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="role_id" value="{{ $role->id }}">
                <div class="modal-body">
                    <!-- Role Details Section -->
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="edit_name_{{ $role->id }}" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name_{{ $role->id }}" name="name" value="{{ $role->name }}" required>
                            <div class="form-text">Enter a unique name for this role</div>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_status_{{ $role->id }}" class="form-label">Status</label>
                            <select class="form-select" id="edit_status_{{ $role->id }}" name="status">
                                <option value="active" {{ $role->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $role->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_description_{{ $role->id }}" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_description_{{ $role->id }}" name="description" placeholder="Brief description of this role" value="{{ $role->description }}">
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Permissions Header with Tools -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Role Permissions</h5>
                                <div class="d-flex align-items-center">
                                    <div class="input-group me-3" style="width: 300px;">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control border-start-0" id="editPermissionSearch_{{ $role->id }}" placeholder="Search permissions..." onkeyup="searchEditPermissions({{ $role->id }})">
                                        <button type="button" class="btn btn-outline-secondary" onclick="clearEditPermissionSearch({{ $role->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary" onclick="selectAllEditPermissions({{ $role->id }})">
                                            <i class="fas fa-check-square me-1"></i> Select All
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="deselectAllEditPermissions({{ $role->id }})">
                                            <i class="fas fa-square me-1"></i> Deselect All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Nav Tabs -->
                    <ul class="nav nav-tabs mb-3" id="editPermissionTabs_{{ $role->id }}" role="tablist">
                        @foreach($permissionGroups as $group => $permissions)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="edit-tab-{{ Str::slug($group) }}-{{ $role->id }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#edit-content-{{ Str::slug($group) }}-{{ $role->id }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ Str::slug($group) }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <span>{{ ucfirst($group) }}</span>
                                <span class="badge ms-2 edit-group-counter" id="edit_group_counter_{{ Str::slug($group) }}_{{ $role->id }}">0/{{ count($permissions) }}</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Permissions Tab Content -->
                    <div class="tab-content" id="editPermissionTabsContent_{{ $role->id }}">
                        @foreach($permissionGroups as $group => $permissions)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                             id="edit-content-{{ Str::slug($group) }}-{{ $role->id }}"
                             role="tabpanel"
                             aria-labelledby="edit-tab-{{ Str::slug($group) }}-{{ $role->id }}">

                            <div class="card">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input edit-group-checkbox"
                                               type="checkbox"
                                               id="edit_group_{{ Str::slug($group) }}_{{ $role->id }}"
                                               onchange="toggleEditGroupPermissions('{{ Str::slug($group) }}', {{ $role->id }})">
                                        <label class="form-check-label fw-bold" for="edit_group_{{ Str::slug($group) }}_{{ $role->id }}">
                                            Select All {{ ucfirst($group) }} Permissions
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($permissions->chunk(ceil(count($permissions) / 3)) as $permissionChunk)
                                        <div class="col-md-4">
                                            @foreach($permissionChunk as $permission)
                                            <div class="permission-item" data-group="{{ Str::slug($group) }}" data-name="{{ strtolower($permission->name) }}">
                                                <div class="form-check">
                                                    <input class="form-check-input edit-permission-checkbox {{ Str::slug($group) }}-edit-checkbox-{{ $role->id }}"
                                                           type="checkbox"
                                                           value="{{ $permission->id }}"
                                                           id="edit_permission_{{ $permission->id }}_{{ $role->id }}"
                                                           name="permissions[]"
                                                           {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                                           onchange="updateEditGroupCounter('{{ Str::slug($group) }}', {{ $role->id }})">
                                                    <label class="form-check-label fw-medium" for="edit_permission_{{ $permission->id }}_{{ $role->id }}">
                                                        {{ ucfirst($permission->name) }}
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block permission-description">{{ $permission->description ?? 'No description' }}</small>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach

<script>
    // Permission search functionality for Edit modal
    function searchEditPermissions(roleId) {
        const searchInput = document.getElementById(`editPermissionSearch_${roleId}`);
        const searchTerm = searchInput.value.toLowerCase();
        const permissionItems = document.querySelectorAll(`#editRoleModal${roleId} .permission-item`);

        // Track which tabs have matching items
        const tabsWithMatches = new Set();

        permissionItems.forEach(item => {
            const permissionName = item.getAttribute('data-name');
            const groupName = item.getAttribute('data-group');

            if (permissionName.includes(searchTerm) || groupName.includes(searchTerm)) {
                item.style.display = '';
                tabsWithMatches.add(groupName);
            } else {
                item.style.display = 'none';
            }
        });

        // Show first tab with matches if current tab has no matches
        if (searchTerm && tabsWithMatches.size > 0) {
            const currentActiveTab = document.querySelector(`#editRoleModal${roleId} .tab-pane.active`);
            const currentActiveTabId = currentActiveTab.id.replace(`edit-content-`, '').replace(`-${roleId}`, '');

            if (!tabsWithMatches.has(currentActiveTabId)) {
                const firstMatchingTab = Array.from(tabsWithMatches)[0];
                const tabToActivate = document.querySelector(`#edit-tab-${firstMatchingTab}-${roleId}`);
                const tabPaneToActivate = document.querySelector(`#edit-content-${firstMatchingTab}-${roleId}`);

                // Remove active classes
                document.querySelectorAll(`#editRoleModal${roleId} .nav-link`).forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll(`#editRoleModal${roleId} .tab-pane`).forEach(pane => {
                    pane.classList.remove('show');
                    pane.classList.remove('active');
                });

                // Add active classes to matching tab
                tabToActivate.classList.add('active');
                tabPaneToActivate.classList.add('show', 'active');
            }
        }

        // Update search result count in tab badges
        updateEditSearchCounts(searchTerm, roleId);
    }

    function updateEditSearchCounts(searchTerm, roleId) {
        if (!searchTerm) return;

        const permissionGroups = document.querySelectorAll(`#editRoleModal${roleId} [id^="edit-content-"]`);
        permissionGroups.forEach(group => {
            const groupId = group.id.replace(`edit-content-`, '').replace(`-${roleId}`, '');
            const visibleItems = group.querySelectorAll('.permission-item[style=""]').length;
            const tabBadge = document.querySelector(`#edit-tab-${groupId}-${roleId} .badge`);

            if (visibleItems > 0) {
                tabBadge.innerHTML += ` <small>(${visibleItems} matches)</small>`;
            }
        });
    }

    function clearEditPermissionSearch(roleId) {
        const searchInput = document.getElementById(`editPermissionSearch_${roleId}`);
        searchInput.value = '';

        const permissionItems = document.querySelectorAll(`#editRoleModal${roleId} .permission-item`);
        permissionItems.forEach(item => {
            item.style.display = '';
        });

        // Reset tab badges
        const groups = document.querySelectorAll(`#editRoleModal${roleId} .edit-group-checkbox`);
        groups.forEach(group => {
            const groupId = group.id.replace(`edit_group_`, '').replace(`_${roleId}`, '');
            updateEditGroupCounter(groupId, roleId);
        });
    }

    // Group permission toggle for Edit modal
    function toggleEditGroupPermissions(groupSlug, roleId) {
        const groupCheckbox = document.getElementById(`edit_group_${groupSlug}_${roleId}`);
        const permissionCheckboxes = document.querySelectorAll(`.${groupSlug}-edit-checkbox-${roleId}`);

        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = groupCheckbox.checked;
        });

        updateEditGroupCounter(groupSlug, roleId);
    }

    // Update group counter for Edit modal
    function updateEditGroupCounter(groupSlug, roleId) {
        const totalCheckboxes = document.querySelectorAll(`.${groupSlug}-edit-checkbox-${roleId}`).length;
        const checkedCheckboxes = document.querySelectorAll(`.${groupSlug}-edit-checkbox-${roleId}:checked`).length;
        const counterElement = document.getElementById(`edit_group_counter_${groupSlug}_${roleId}`);

        counterElement.textContent = `${checkedCheckboxes}/${totalCheckboxes}`;

        // Update group checkbox state
        const groupCheckbox = document.getElementById(`edit_group_${groupSlug}_${roleId}`);
        groupCheckbox.checked = checkedCheckboxes === totalCheckboxes;
        groupCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;

        // Update counter color based on selection status
        if (checkedCheckboxes === 0) {
            counterElement.className = 'badge bg-secondary ms-2 edit-group-counter';
        } else if (checkedCheckboxes === totalCheckboxes) {
            counterElement.className = 'badge bg-success ms-2 edit-group-counter';
        } else {
            counterElement.className = 'badge bg-primary ms-2 edit-group-counter';
        }

        // Update tab badge color
        const tabBadge = document.querySelector(`#edit-tab-${groupSlug}-${roleId} .badge`);
        tabBadge.className = counterElement.className;
    }

    // Select/deselect all permissions for Edit modal
    function selectAllEditPermissions(roleId) {
        const permissionCheckboxes = document.querySelectorAll(`#editRoleModal${roleId} .edit-permission-checkbox`);
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });

        // Update all group counters and checkboxes
        const groups = document.querySelectorAll(`#editRoleModal${roleId} .edit-group-checkbox`);
        groups.forEach(group => {
            const groupId = group.id.replace(`edit_group_`, '').replace(`_${roleId}`, '');
            updateEditGroupCounter(groupId, roleId);
        });
    }

    function deselectAllEditPermissions(roleId) {
        const permissionCheckboxes = document.querySelectorAll(`#editRoleModal${roleId} .edit-permission-checkbox`);
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        // Update all group counters and checkboxes
        const groups = document.querySelectorAll(`#editRoleModal${roleId} .edit-group-checkbox`);
        groups.forEach(group => {
            const groupId = group.id.replace(`edit_group_`, '').replace(`_${roleId}`, '');
            updateEditGroupCounter(groupId, roleId);
        });
    }

    // Initialize counters on modal shown
    document.addEventListener('DOMContentLoaded', function() {
        // Set up event listeners for each edit modal
        @foreach($roles as $role)
            document.getElementById('editRoleModal{{ $role->id }}').addEventListener('shown.bs.modal', function() {
                const groups = document.querySelectorAll(`#editRoleModal{{ $role->id }} .edit-group-checkbox`);
                groups.forEach(group => {
                    const groupId = group.id.replace(`edit_group_`, '').replace(`_{{ $role->id }}`, '');
                    updateEditGroupCounter(groupId, {{ $role->id }});
                });
            });
        @endforeach
    });
</script>

<style>
    /* Improved styling for permissions structure */
    .permission-item {
        padding: 10px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
        margin-bottom: 5px;
    }

    .permission-item:hover {
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    .permission-item:last-child {
        border-bottom: none;
    }

    .permission-description {
        font-size: 80%;
        margin-left: 1.7rem;
    }

    /* Tab styling */
    .nav-tabs .nav-link {
        color: #6c757d;
        border-radius: 0;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        font-weight: 600;
    }

    .nav-tabs .badge {
        font-size: 75%;
        font-weight: normal;
    }

    /* Search field improvement */
    [id^="editPermissionSearch"]:focus {
        box-shadow: none;
        border-color: var(--primary-color);
    }

    /* Make the form-check-input slightly larger */
    .form-check-input {
        width: 1.1em;
        height: 1.1em;
    }

    /* Add a subtle hover effect to checkboxes */
    .form-check-input:hover {
        cursor: pointer;
        box-shadow: 0 0 0 0.1rem rgba(79, 70, 229, 0.2);
    }

    /* Improve tab content appearance */
    .tab-content {
        min-height: 300px;
        max-height: 450px;
        overflow-y: auto;
    }

    /* Add subtle animations */
    .tab-pane.fade {
        transition: opacity 0.15s linear;
    }

    /* Adjust spacing for permission groups */
    .permission-group-title {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }
</style>
