<!-- resources/views/components/modals/add-card-modal.blade.php -->
<div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCardModalLabel">
                    <i class="fas fa-plus-circle me-2" style="color: var(--primary-color)"></i>Add New Card
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cards.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tel" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="tel" name="tel" required>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="serial_number" name="serial_number"
                                    value="{{ 'CARD-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="generateSerialNumber()">
                                    <i class="fas fa-sync-alt"></i> Generate
                                </button>
                            </div>
                            <small class="text-muted">Serial number is auto-generated but can be modified</small>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Card
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function generateSerialNumber() {
        const randomNum = Math.floor(Math.random() * 9999) + 1;
        const serialNumber = 'CARD-' + String(randomNum).padStart(4, '0');
        document.getElementById('serial_number').value = serialNumber;
    }
</script>
