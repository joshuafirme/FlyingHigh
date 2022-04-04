<!-- Create update -->
<div class="modal fade" id="postModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="qty" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Buffer Stock</label>
                    <input type="number" class="form-control" name="buffer_stock" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">JTE lot code</label>
                    <input type="text" class="form-control" name="jde_lot_code">
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Supplier lot code</label>
                    <input type="text" class="form-control" name="supplier_lot_code">
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Expiration</label>
                    <input type="date" class="form-control" name="expiration" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="validationCustom04" class="form-label">Status</label>
                    <select class="form-control" name="status" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div><!-- End Create update -->

<!-- Transfer modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="transfer-form" action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Transfer to Hub</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12 mt-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" required readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" required readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="validationCustom04" class="form-label">Hub</label>
                    <select class="form-control" name="hub_id" required>
                        <option selected disabled value="">Choose...</option>
                        @foreach ($hubs as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="stock" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" id="btn-transfer" type="submit">Transfer</button>
            </div>
        </form>
    </div>
</div><!-- End transfer modal -->
