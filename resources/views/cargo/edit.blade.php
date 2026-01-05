<div class="modal fade" id="editCargoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editCargoForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Cargo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editLabel" class="form-label">Label</label>
                        <input type="text" name="label" id="editLabel" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="editPackaging" class="form-label">Packaging</label>
                        <input type="text" name="packaging" id="editPackaging" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

            </form>
        </div>
    </div>
</div>
