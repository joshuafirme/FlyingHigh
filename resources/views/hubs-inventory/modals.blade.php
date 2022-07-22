<div class="modal fade" id="pickupModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form action="#" method="post" class="modal-content" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Pickup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3 mb-3">
                <div class="col-12">
                    <b>Line Items</b>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Order Id</th>
                                <th scope="col">Item</th>
                                <th scope="col">Tracking No</th>
                                <th scope="col">Qty Ordered</th>
                                <th scope="col">Qty Shipped</th>
                                <th scope="col">Ship Date Time</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="tbl-pickup-details">

                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- ship modal -->
<div class="modal fade" id="addPickupModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="pickup-form" class="modal-content" action="#" autocomplete="off">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Shipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Shiment ID</label>
                    <input type="text" class="form-control" name="shipmentId" id="shipmentId-input"
                        placeholder="Enter Shiment ID">
                </div>
                <div class="col-md-12 mt-2 mb-2 alert-message">
                </div>
                <div class="col-md-12 mb-2">
                    <hr>
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
                            $weights = $attribute->where('type', 'weightUnit')->get();
                        @endphp
                        @foreach ($weights as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Freight Charges</label>
                    <input type="number" step=".01" class="form-control" name="freightCharges" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Qty Packages</label>
                    <input type="number" class="form-control" name="qtyPackages">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="col-form-label">Tracking #</label>
                    <input type="number" class="form-control" name="trackingNo">
                </div>

                <!-- hub_id -->
                <input type="hidden" name="hub_id" value="{{ $branch_id }}">

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

                <div class="col-md-12 mt-2  mb-2">
                    <b>Line Items</b>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="2%" scope="col">Line #</th>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Description</th>
                                    <th width="5%" scope="col">Qty Ordered</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-pickup-items">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Add</button>
            </div>
        </form>
    </div>
</div>
