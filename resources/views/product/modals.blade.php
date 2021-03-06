<!-- Create update -->
<div class="modal fade" id="postModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4 mt-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="itemNumber" required>
                </div>
                <div class="col-md-4 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="productDescription" required>
                </div>
                <div class="col-md-4 mt-3">
                    <label class="form-label">Barcode</label>
                    <input type="text" class="form-control" name="barCodeNumber">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Buffer Stock</label>
                    <input type="number" class="form-control" name="bufferStock" required>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Base UOM</label>
                    <select class="form-control" name="baseUOM" required>
                        <option selected disabled value="">Choose...</option>
                        @foreach ($attribute->where('type', 'baseUOM')->get() as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Weight Unit</label>
                    <select class="form-control" name="weightUnit" required>
                        <option selected disabled value="">Choose...</option>
                        @foreach ($attribute->where('type', 'weightUnit')->get() as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Volume Uom</label>
                    <select class="form-control" name="volumeUom" required>
                        <option selected disabled value="">Choose...</option>
                        @foreach ($attribute->where('type', 'volumeUom')->get() as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Conversion Factor</label>
                    <input type="text" class="form-control" name="conversionFactor" required>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Height</label>
                    <input type="text" class="form-control" name="height" required>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Width</label>
                    <input type="text" class="form-control" name="width" required>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Depth</label>
                    <input type="text" class="form-control" name="depth" required>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Item Demention Unit</label>
                    <input type="text" class="form-control" name="itemDimensionUnit">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Weight</label>
                    <input type="text" class="form-control" name="weight" required>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Volume</label>
                    <input type="text" class="form-control" name="volume">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Action Code</label>
                    <input type="text" class="form-control" name="actionCode">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Harmonized Code</label>
                    <input type="text" class="form-control" name="harmonizedCode">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Hazardous</label>
                    <input type="text" class="form-control" name="hazardous">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Retail Price</label>
                    <input type="text" class="form-control" name="retailPrice" required>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Special Shipping Code</label>
                    <input type="text" class="form-control" name="specialShippingCode">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Currency Code</label>
                    <input type="text" class="form-control" name="currencyCode">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Line Type</label>
                    <input type="text" class="form-control" name="lineType">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">UnHazardCode</label>
                    <input type="text" class="form-control" name="unHazardCode">
                </div>
                <div class="col-md-12 mt-3 row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="food">
                            <label class="form-check-label">
                                Food
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="refrigerated">
                            <label class="form-check-label">
                                Refrigerated
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="willMelt">
                            <label class="form-check-label">
                                Will Melt
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="willFreeze">
                            <label class="form-check-label">
                                Will Freeze
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="isBarcoded">
                            <label class="form-check-label">
                                Is Barcoded
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="isLotControlled">
                            <label class="form-check-label">
                                Is Lot Controlled
                            </label>
                        </div>
                    </div>
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
                    <input type="text" class="form-control" name="sku" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Lot Code</label>
                    <select class="form-control" name="lot_code" required>
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="current_stock" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Expiration</label>
                    <input type="text" class="form-control" name="expiration" readonly>
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
                <h5 class="modal-title">Stock Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body g-3">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Excel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">API</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                        aria-labelledby="home-tab">
                        <form action="#" method="POST" enctype="multipart/form-data" class="row">
                            <div class="col-12 mt-3">
                                <div class="form-group mb-4" style="max-width: 500px;">
                                    <div class="custom-file text-left">
                                        <input type="file" name="file" class="custom-file-input"
                                            id="customFile" required>
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button class="btn btn-sm btn-primary" type="submit">Import</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade row" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="col-md-12 mt-3">
                            <label class="form-label">API Endpoint</label>
                            <input type="text" class="form-control" name="api_endpoint" required
                                value="{{ url('/purchase_order.json') }}">
                        </div>
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-sm btn-primary" type="button" id="btn-fetch">Fetch</button>
                        </div>

                        <button class="btn btn-sm btn-primary d-none" id="btn-api-import"
                            type="submit">Import</button>
                        <div class="col-12 mt-4">
                            <h5><span id="transactionReferenceNumber"></span></h5>
                            <div id="orders-container"></div>
                        </div>
                    </div>
                </div>

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
                <div class="col-md-6 mt-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Lot Code</label>
                    <select class="form-control" name="lot_code" id="lot_codes">
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

<!-- Transfer modal -->
<div class="modal fade" id="hubsStockModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div action="#" method="post" class="modal-content" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title">Hubs Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <div class="mt-2 mb-4">
                        <h5 id="sku-text"></h5>
                        <h5 id="description-text"></h5>
                    </div>
                    <table class="table table-striped pb-3">
                        <thead>
                            <th>Hub</th>
                            <th>Lot Code</th>
                            <th>Stock</th>
                            <th>Expiration</th>
                        </thead>
                        <tbody id="tbl-hubs-stock">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3 mb-3">
                <div class="col-12">
                    <table class="table table-bordered table-hover tbl-product-details">
                        <thead>
                            <th>Attributes</th>
                            <th>Value</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transfer modal -->
<div class="modal fade" id="barcodeScanModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div action="#" method="post" class="modal-content" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title">Import Stock via Barcode Scan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" class="modal-body row g-3">
                <div class="col-12 mt-2">
                    <label class="form-label">Scan Barcode</label>
                    <input type="text" class="form-control" id="barcode-scan-input">
                    <small class="text-danger error-message d-none">Barcode not found.</small>
                </div>
                <div class="col-12 mt-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="qty" value="1" min="1">
                </div>
                <div class="col-12 mt-3">
                    <hr>
                </div>
                <div class="col-12">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" readonly>
                </div>
                <div class="col-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" readonly>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
