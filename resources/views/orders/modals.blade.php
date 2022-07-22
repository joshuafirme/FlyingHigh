<!-- orders modal -->
<div class="modal fade" id="ordersModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12 d-md-flex flex-md-wrap">
                    <div class="mb-4 ml-auto"> <span class="text-muted">Order Details:</span>
                        <div class="d-flex flex-wrap wmin-md-400">
                            <ul class="list list-unstyled mb-0 text-left">
                                <li>Shipment ID</li>
                                <li>Order ID</li>
                                <li>Order type</li>
                                <li>Ship Method</li>
                                <li>Ship Carrier</li>
                                <li>Date:</li>
                                <li>Status:</li>
                            </ul>
                            <ul class="list list-unstyled text-right mb-0 ml-auto">
                                <li><span class="font-weight-semibold shipmentId"></span></li>
                                <li><span class="font-weight-semibold orderId"></span>
                                <li class="orderSource"></li>
                                <li><span class="font-weight-semibold shipMethod"></span></li>
                                <li><span class="font-weight-semibold shipCarrier"></span></li>
                                <li><span class="dateTimeSubmittedIso"></span></li>
                                <li><span class="font-weight-semibold status"></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-md-flex flex-md-wrap">
                    <div class="mb-4 mb-md-2 text-left"> <span class="text-muted">Customer Info:</span>
                        <ul class="list list-unstyled mb-0">
                            <li>
                                <h5 class="my-2 custName"></h5>
                            </li>
                            <li>TIN: <span class="customerTIN"></span></li>
                            <li>Ship Add 1: <span class="shipAddr1"></span></li>
                            <li>Ship Add 2: <span class="shipAddr2"></span></li>

                            <li class="shipCity"></li>
                            <li class="shipState"></li>
                            <li class="shipZip"></li>
                            <li class="shipPhone"></li>
                            <li><a href="# customerEmail"></a></li>
                        </ul>
                    </div>
                    <div class="mb-2 ml-auto"> <span class="text-muted">Payment Details:</span>
                        <div class="d-flex flex-wrap wmin-md-400">
                            <ul class="list list-unstyled mb-0 text-left">
                                <li>Shipping Charge Amount:</li>
                                <li>Sales Tax Amount:</li>
                                <li>Shipping Tax Total Amount:</li>
                                <li>
                                    <h5 class="my-2">Package Total:</h5>
                                </li>
                            </ul>
                            <ul class="list list-unstyled text-right mb-0 ml-auto">
                                <li><span class="font-weight-semibold shippingChargeAmount"></span></li>
                                <li><span class="font-weight-semibold salesTaxAmount"></span>
                                <li><span class="font-weight-semibold shippingTaxTotalAmount"></span>
                                </li>
                                <li>
                                    <h5 class="font-weight-semibold my-2">₱<span class="packageTotal"></span></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h4 class="page-title">Line Items</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    Item Details
                                </th>
                                <th>Pv</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Sales Price</th>
                                <th>Taxable Amount</th>
                                <th>Line Item Total</th>
                            </tr>
                        </thead>
                        <tbody class="tbl-orders-details">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- End orders modal -->

<div class="modal fade" id="hubModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Ship</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Tag as Picked Up</button>
            </div>
        </div>
    </div>
</div>

<!-- ship modal -->
<div class="modal fade" id="shipModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="ship-form" class="modal-content" action="#" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Ship</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Shiment ID</label>
                    <input type="number" class="form-control" name="shipmentId" readonly>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Order ID</label>
                    <input type="number" class="form-control" name="orderId" readonly>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Total Weight</label>
                    <input type="number" step=".01" class="form-control" name="totalWeight" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Weight Unit of measure</label>
                    <select class="form-control" name="weightUoM" required>
                        @php
                            $weights = ['MG', 'CG', 'DG', 'G', 'DAG', 'HG', 'KG', 'T'];
                        @endphp
                        @foreach ($weights as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Freight Charges</label>
                    <input type="number" step=".01" class="form-control" name="freightCharges" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Qty Packages</label>
                    <input type="number" class="form-control" name="qtyPackages" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Tracking #</label>
                    <input type="number" class="form-control" name="trackingNo" required>
                </div>
                <div class="col-md-12 mt-2 mb-2">
                    <div id="order-info-container">
                        <div class="ic-responsive-invoice">
                            <table width="100%" cellpadding="0" cellspaceing="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <address class="ic-invoice-addess"><strong>Customer
                                                    Info:</strong><br>
                                                <p class="mb-0">
                                                    <span id="custName"></span>
                                                </p>
                                                <p class="mb-0">
                                                    <span id="customerEmail"></span>
                                                </p>
                                                <p class="mb-0">TIN:
                                                    <span id="customerTIN"></span>
                                                </p>
                                                <p class="mb-0">Shipping Address:
                                                    <span id="shipAddr1"></span>
                                                </p>
                                                <p class="mb-0">Phone:
                                                    <span id="shipPhone"></span>
                                                </p>
                                                </p>
                                            </address>
                                        </td>
                                        <td>
                                            <address class="ic-invoice-addess ic-right-content">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <th>Payment Method
                                                        </th>
                                                        <th>Amount</th>
                                                    </thead>
                                                    <tbody id="tbl-payment-method">

                                                    </tbody>
                                                </table>
                                                <p class="mb-0">Shipping Charge Amount:
                                                    ₱
                                                    <span id="shippingChargeAmount"></span>
                                                </p>
                                                <p class="mb-0">Sales Tax Amount:
                                                    ₱
                                                    <span id="salesTaxAmount"></span>
                                                </p>
                                                <p class="mb-0">Shipping Tax Total Amount:
                                                    ₱
                                                    <span id="shippingTaxTotalAmount"></span>
                                                </p>
                                                <p class="mb-0">Package Total:
                                                    ₱
                                                    <span id="packageTotal"></span>
                                                </p>
                                            </address>
                                        </td>
                                        <td>
                                            <address class="ic-invoice-addess ic-right-content">
                                                <p class="mb-0">Shipment ID:
                                                    <span id="shipmentId"></span>
                                                </p>
                                                <p class="mb-0">Order Type:
                                                    <span id="orderSource"></span>
                                                </p>
                                                <p class="mb-0">Ship Method:
                                                    <span id="shipMethod"></span>
                                                </p>
                                                <p class="mb-0">Ship Carrier:
                                                    <span id="shipCarrier"></span>
                                                </p>
                                                <p class="mb-0">Date Time Submitted:
                                                    <span id="dateTimeSubmittedIso"></span>
                                                </p>
                                            </address>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <h4 class="page-title">Line Items</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    Item Details
                                </th>
                                <th>Pv</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Sales Price</th>
                                <th>Taxable Amount</th>
                                <th>Line Item Total</th>
                                <!--  <th>Qty To Ship</th>
                                <th>Lot Code</th>-->
                            </tr>
                        </thead>
                        <tbody class="tbl-ship-items">

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Ship</button>
            </div>
        </form>
    </div>
</div>


<!-- orders modal -->
<div class="modal fade" id="hubModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Hub</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Tag as Picked Up</button>
            </div>
        </div>
    </div>
</div>

<!-- Return modal -->
<div class="modal fade mt-5" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <form id="return-form" class="modal-content" action="#" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Return</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12 mb-2">
                    <label class="col-form-label">Order ID</label>
                    <input type="number" class="form-control" name="orderId" readonly>
                </div>

                <div class="col-md-12 mb-2">
                    <label class="col-form-label">SKU</label>
                    <input type="number" class="form-control" name="sku" readonly>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="col-form-label">Lot Code</label>
                    <select class="form-control" name="lot_code" id="lot_codes" required>
                        <option value="" disabled selected>Choose lot code</option>
                    </select>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="col-form-label">RMA Number</label>
                    <input class="form-control" name="rma_number" required>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="col-form-label">Quantity</label>
                    <input type="number" class="form-control" name="qty" required>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="col-form-label">Reason</label>
                    <select class="form-control" name="reason" id="reason" required>
                        <option value="" disabled selected>Choose reason</option>
                        @foreach ($reasons as $item)
                            <option value="{{ $item->id }}">{{ $item->reason }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Return</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="fetchOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Fetch Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home"
                                role="tab" aria-controls="nav-home" aria-selected="true">Shipments</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile"
                                role="tab" aria-controls="nav-profile" aria-selected="false">Single Shipment</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active row" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="col-md-6 mt-3 mb-3">
                                <label class="col-form-label">Number of Shipment</label>
                                <input type="number" class="form-control" name="shipment_count" min="1">
                            </div>
                            <div class="col-md-6 mt-3 mb-3">
                                <button class="btn btn-sm btn-outline-primary" id="btn-bulk-fetch">Fetch</button>
                            </div>
                        </div>
                        <div class="tab-pane fade row" id="nav-profile" role="tabpanel"
                            aria-labelledby="nav-profile-tab">
                            <div class="col-md-6 mt-3 mb-3">
                                <label class="col-form-label">Shipment ID</label>
                                <input type="text" class="form-control" name="shipmentId">
                            </div>
                            <div class="col-md-6 mt-3 mb-3">
                                <button class="btn btn-sm btn-outline-primary" id="btn-single-fetch">Fetch</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
