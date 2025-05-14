<!-- Add Role Modal - Redesigned with improved permissions organization -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="addRoleModalLabel">
                    <i class="fas fa-plus-circle me-2" style="color: var(--primary-color)"></i>Add New Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Role Details Section -->
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="form-text">Enter a unique name for this role</div>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Brief description of this role">
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
                                        <input type="text" class="form-control border-start-0" id="permissionSearch" placeholder="Search permissions..." onkeyup="searchPermissions()">
                                        <button type="button" class="btn btn-outline-secondary" onclick="clearPermissionSearch()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary" onclick="selectAllPermissions()">
                                            <i class="fas fa-check-square me-1"></i> Select All
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="deselectAllPermissions()">
                                            <i class="fas fa-square me-1"></i> Deselect All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Nav Tabs -->
                    <ul class="nav nav-tabs mb-3" id="permissionTabs" role="tablist">
                        @foreach($permissionGroups as $group => $permissions)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="tab-{{ Str::slug($group) }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#content-{{ Str::slug($group) }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ Str::slug($group) }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <span>{{ ucfirst($group) }}</span>
                                <span class="badge ms-2 group-counter" id="group_counter_{{ Str::slug($group) }}">0/{{ count($permissions) }}</span>
                            </button>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Permissions Tab Content -->
                    <div class="tab-content" id="permissionTabsContent">
                        @foreach($permissionGroups as $group => $permissions)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                             id="content-{{ Str::slug($group) }}"
                             role="tabpanel"
                             aria-labelledby="tab-{{ Str::slug($group) }}">

                            <div class="card">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input group-checkbox"
                                               type="checkbox"
                                               id="group_{{ Str::slug($group) }}"
                                               onchange="toggleGroupPermissions('{{ Str::slug($group) }}')">
                                        <label class="form-check-label fw-bold" for="group_{{ Str::slug($group) }}">
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
                                                    <input class="form-check-input permission-checkbox {{ Str::slug($group) }}-checkbox"
                                                           type="checkbox"
                                                           value="{{ $permission->id }}"
                                                           id="permission_{{ $permission->id }}"
                                                           name="permissions[]"
                                                           onchange="updateGroupCounter('{{ Str::slug($group) }}')">
                                                    <label class="form-check-label fw-medium" for="permission_{{ $permission->id }}">
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
                        <i class="fas fa-save me-1"></i> Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Permission search functionality
    function searchPermissions() {
        const searchInput = document.getElementById('permissionSearch');
        const searchTerm = searchInput.value.toLowerCase();
        const permissionItems = document.querySelectorAll('.permission-item');

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
            const currentActiveTab = document.querySelector('.tab-pane.active');
            const currentActiveTabId = currentActiveTab.id.replace('content-', '');

            if (!tabsWithMatches.has(currentActiveTabId)) {
                const firstMatchingTab = Array.from(tabsWithMatches)[0];
                const tabToActivate = document.querySelector(`#tab-${firstMatchingTab}`);
                const tabPaneToActivate = document.querySelector(`#content-${firstMatchingTab}`);

                // Remove active classes
                document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show');
                    pane.classList.remove('active');
                });

                // Add active classes to matching tab
                tabToActivate.classList.add('active');
                tabPaneToActivate.classList.add('show', 'active');
            }
        }

        // Update search result count in tab badges
        updateSearchCounts(searchTerm);
    }

    function updateSearchCounts(searchTerm) {
        if (!searchTerm) return;

        const permissionGroups = document.querySelectorAll('[id^="content-"]');
        permissionGroups.forEach(group => {
            const groupId = group.id.replace('content-', '');
            const visibleItems = group.querySelectorAll('.permission-item[style=""]').length;
            const tabBadge = document.querySelector(`#tab-${groupId} .badge`);

            if (visibleItems > 0) {
                tabBadge.innerHTML += ` <small>(${visibleItems} matches)</small>`;
            }
        });
    }

    function clearPermissionSearch() {
        const searchInput = document.getElementById('permissionSearch');
        searchInput.value = '';

        const permissionItems = document.querySelectorAll('.permission-item');
        permissionItems.forEach(item => {
            item.style.display = '';
        });

        // Reset tab badges
        const groups = document.querySelectorAll('.group-checkbox');
        groups.forEach(group => {
            const groupSlug = group.id.replace('group_', '');
            updateGroupCounter(groupSlug);
        });
    }

    // Group permission toggle
    function toggleGroupPermissions(groupSlug) {
        const groupCheckbox = document.getElementById(`group_${groupSlug}`);
        const permissionCheckboxes = document.querySelectorAll(`.${groupSlug}-checkbox`);

        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = groupCheckbox.checked;
        });

        updateGroupCounter(groupSlug);
    }

    // Update group counter
    function updateGroupCounter(groupSlug) {
        const totalCheckboxes = document.querySelectorAll(`.${groupSlug}-checkbox`).length;
        const checkedCheckboxes = document.querySelectorAll(`.${groupSlug}-checkbox:checked`).length;
        const counterElement = document.getElementById(`group_counter_${groupSlug}`);

        counterElement.textContent = `${checkedCheckboxes}/${totalCheckboxes}`;

        // Update group checkbox state
        const groupCheckbox = document.getElementById(`group_${groupSlug}`);
        groupCheckbox.checked = checkedCheckboxes === totalCheckboxes;
        groupCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;

        // Update counter color based on selection status
        if (checkedCheckboxes === 0) {
            counterElement.className = 'badge bg-secondary ms-2 group-counter';
        } else if (checkedCheckboxes === totalCheckboxes) {
            counterElement.className = 'badge bg-success ms-2 group-counter';
        } else {
            counterElement.className = 'badge bg-primary ms-2 group-counter';
        }

        // Update tab badge color
        const tabBadge = document.querySelector(`#tab-${groupSlug} .badge`);
        tabBadge.className = counterElement.className;
    }

    // Select/deselect all permissions
    function selectAllPermissions() {
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });

        // Update all group counters and checkboxes
        const groups = document.querySelectorAll('.group-checkbox');
        groups.forEach(group => {
            const groupSlug = group.id.replace('group_', '');
            updateGroupCounter(groupSlug);
        });
    }

    function deselectAllPermissions() {
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        // Update all group counters and checkboxes
        const groups = document.querySelectorAll('.group-checkbox');
        groups.forEach(group => {
            const groupSlug = group.id.replace('group_', '');
            updateGroupCounter(groupSlug);
        });
    }

    // Initialize counters on modal open
    document.getElementById('addRoleModal').addEventListener('shown.bs.modal', function () {
        const groups = document.querySelectorAll('.group-checkbox');
        groups.forEach(group => {
            const groupSlug = group.id.replace('group_', '');
            updateGroupCounter(groupSlug);
        });
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
    #permissionSearch:focus {
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
