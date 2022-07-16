<div class="modal fade" id="postModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="#" method="post" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12">
                    <label for="validationCustom01" class="form-label">Attribut name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Type</label>
                    <select class="form-control" name="type" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="baseUOM">Base UOM</option>
                        <option value="weightUnit">Weight Unit</option>
                        <option value="volumeUom">Volume UOM</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div><!-- End Large Modal-->
