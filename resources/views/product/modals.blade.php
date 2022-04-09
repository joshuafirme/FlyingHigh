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
                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="qty" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Buffer Stock</label>
                    <input type="number" class="form-control" name="buffer_stock" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">JTE lot code</label>
                    <input type="text" class="form-control" name="jde_lot_code">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Supplier lot code</label>
                    <input type="text" class="form-control" name="supplier_lot_code">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Expiration</label>
                    <input type="date" class="form-control" name="expiration">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="validationCustom04" class="form-label">Status</label>
                    <select class="form-control" name="status" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-sm-6 mt-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="has_bundle" id="has_bundle">
                        <label class="form-check-label">Bundled</label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 mt-2 d-none bundle-choices">
                    <label class="col-form-label" for="choices-multiple-remove-button">SKU Bundles</label>
                    <select class="form-control" name="bundles[]" id="choices-multiple-remove-button"
                        placeholder="Select SKU bundle" required multiple>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
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
                <h5 class="modal-title">Hub Transfer</h5>
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

<!-- Import modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('importProduct') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Import Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
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
</div><!-- End Import modal -->

<div class="modal fade" id="apiModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="import-via-api-form" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Import Stock Via API</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12 mt-3">
                    <label class="form-label">API Endpoint</label>
                    <input type="text" class="form-control" name="api_endpoint" required
                        value="{{ env('APP_URL') }}/purchase_order.json">
                </div>
                <div class="col-md-12 mt-3">
                    <button class="btn btn-sm btn-primary" type="button" id="btn-fetch">Fetch</button>
                </div>

                <div class="col-12 mt-4">
                    <h5><span id="transactionReferenceNumber"></span></h5>
                    <div id="orders-container"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" id="btn-api-import" type="submit">Import</button>
            </div>
        </form>
    </div>
</div>


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
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="qty" required>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                    <label class="col-form-label">Action</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="add" value="add" checked
                            required>
                        <label class="form-check-label" for="add">
                            Add
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="less" value="less" required>
                        <label class="form-check-label" for="less">
                            Less
                        </label>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Remarks</label>
                    <select class="form-control" name="remarks" required>
                        <option selected disabled value="">Choose a remarks...</option>
                        <option value="Physical count descrepancy">Physical count descrepancy</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Owner used">Owner used</option>
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


<!-- Bulk Transfer modal -->
<div class="modal fade" id="bulkTransferModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="bulk-transfer-form" action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Bulk Hub Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12 mt-2">
                    <label class="col-form-label" for="choices-multiple-remove-button">SKU</label>
                    <select class="form-control" name="bundles[]" id="choices-multiple-sku"
                        placeholder="Select SKU bundle" required multiple>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <div id="multi-sku-input" class="pb-3 border rounded bg-light">

                    </div>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" id="btn-bulk-transfer" type="submit">Transfer</button>
            </div>
        </form>
    </div>
</div>
