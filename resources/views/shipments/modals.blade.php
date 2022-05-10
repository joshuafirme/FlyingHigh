<!-- Pickup modal -->
<div class="modal fade" id="pickupModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Shipment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12 d-md-flex flex-md-wrap">
                    <div class="mb-4">
                        <div class="d-flex flex-wrap wmin-md-400">
                            <ul class="list list-unstyled mb-0 text-left">
                                <li>Shipment ID:</li>
                                <li>Ship Carrier:</li>
                                <li>Ship Method:</li>
                                <li>Total Weight:</li>
                                <li>Freight Charges:</li>
                                <li>Qty Packages:</li>
                                <li>Curr Code:</li>
                            </ul>
                            <ul class="list list-unstyled text-right mb-0 ml-auto">
                                <li><span class="font-weight-semibold shipmentId"></span></li>
                                <li><span class="font-weight-semibold shipCarrier"></span></li>
                                <li><span class="font-weight-semibold shipMethod"></span></li>
                                <li><span class="font-weight-semibold totalWeight"></span> <span class="font-weight-semibold weightUoM"></span></li>
                                <li><span class="font-weight-semibold freightCharges"></span></li>
                                <li><span class="font-weight-semibold qtyPackages"></span></li>
                                <li><span class="font-weight-semibold currCode"></span></li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <b>Line Items</b>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Tracking No</th>
                                <th scope="col">Qty Ordered</th>
                                <th scope="col">Qty Shipped</th>
                                <th scope="col">Ship Date Time</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-pickup-details">

                        </tbody>
                    </table>
                </div>
            </div>
            <form id="pickup-form" class="modal-footer" method="POST">
                @csrf
                <select class="form-control float-right" style="width:auto" name="hub_id" required>
                    <option value="" disabled selected>Choose hub</option>
                    @foreach ($hubs as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary btn-tag-all-as-delivered" type="submit">Tag as Delivered</button>
            </form>
        </div>
    </div>
</div><!-- End Pickup modal -->


<!-- Pickup modal -->
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
