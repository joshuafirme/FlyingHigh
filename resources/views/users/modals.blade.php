<div class="modal fade" id="userModal" tabindex="-1">
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
                    <label for="validationCustom01" class="form-label">Full name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Role</label>
                    <select class="form-control" name="access_level" required>
                        <option selected disabled value="">Choose...</option>
                        @foreach ($roles as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Please select a valid state.
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Assigned Branch Plant</label>
                    <select class="form-control" name="branch_id" required>
                        <option selected disabled value="">Choose...</option>
                        @foreach ($branches as $item)
                            <option value="{{ $item->receiver }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="validationCustom02" class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Status</label>
                    <select class="form-control" name="status" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select a valid state.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div><!-- End Large Modal-->
