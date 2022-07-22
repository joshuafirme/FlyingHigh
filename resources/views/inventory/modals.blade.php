<!-- Stock Adjustment modal -->
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="stock-adjustment-form" action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Stock Adjustment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12 mt-3">
                    <a class="btn btn-sm btn-primary float-right stock-adjustment-link" href="#"
                        target="_blank"><i class="fas fa-file-export"></i> View Stock Adjustment History</a>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="productDescription" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Lot Code</label>
                    <input type="number" class="form-control" name="lot_code" readonly>
                    </select>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Expiration</label>
                    <input type="text" class="form-control" name="expiration" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Quantity to adjust</label>
                    <input type="number" class="form-control" name="qty" required>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                    <label class="col-form-label">Action</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="add" value="add"
                            checked required>
                        <label class="form-check-label" for="add">
                            Add
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="less" value="less"
                            required>
                        <label class="form-check-label" for="less">
                            Less
                        </label>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Remarks</label>
                    <select class="form-control" name="remarks_id" required>
                        <option selected disabled value="">Choose a remarks...</option>
                        @foreach ($remarks as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" id="btn-adjust" type="submit">Adjust</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="updateExpModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <form id="updateExpform" action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Update Expiration Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12 mt-2">
                    <label class="col-form-label">SKU</label>
                    <input type="text" class="form-control" name="sku">
                </div>
                <div class="col-md-12 mt-2">
                    <label class="col-form-label">Lot Code</label>
                    <input type="text" class="form-control" name="lot_code">
                </div>
                <div class="col-md-12 mt-2 mb-2">
                    <label class="col-form-label">Expiration</label>
                    <input type="datetime-local" class="form-control" name="expiration">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" id="btn-bulk-transfer" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Transfer modal -->
<div class="modal fade" id="bulkTransferModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="bulk-transfer-form" action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Hub Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12 mt-2">
                    <label class="col-form-label">Barcode Scan</label>
                    <input type="text" class="form-control" id="barcode-scan">
                </div>
                <div class="col-md-12 mt-2">
                    <label class="col-form-label">Search SKU</label>
                    <select class="form-control" id="choices-multiple-sku" placeholder="Select SKU bundle" required>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <table class="table pb-3">
                        <thead>
                            <th scope="col">SKU</th>
                            <th scope="col">Lot Code</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Qty to transfer</th>
                            <th scope="col">Hub</th>
                            <th scope="col">Action</th>
                        </thead>
                        <tbody id="inputs-container">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" id="btn-bulk-transfer" type="submit">Transfer</button>
            </div>
        </form>
    </div>
</div>
