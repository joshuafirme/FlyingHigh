<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ url('/stock-transfer/import') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Import Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">

                <div class="col-md-12 mt-3">
                    <label class="form-label">Tracking #</label>
                    <input type="text" name="tracking_no" class="form-control" required>
                </div>
                <div class="col-12 mt-3">
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile" required>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit">Import</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <form id="transferForm" action="{{ url('/stock-transfer/transfer') }}" method="POST"
            enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <input type="hidden" name="id">
                <div class="col-md-12 mt-1">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Pending Qty</label>
                    <input type="text" name="qty_order" class="form-control" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Qty To Transfer</label>
                    <input type="number" name="qty_transfer" class="form-control" required>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="select-from-old" name="old_lot_code">
                        <label class="form-check-label" for="exampleCheck1">Select from the inventory</label>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Lot Code</label>
                    <input type="text" name="lot_code" class="form-control">
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Expiration</label>
                    <input type="date" name="expiration" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit">Transfer</button>
            </div>
        </form>
    </div>
</div>
