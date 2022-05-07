<!-- Pickup modal -->
<div class="modal fade" id="pickupModal" tabindex="-1">
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
                <div class="col-12 mb-3">
                    <div class="dropdown float-right">
                        <button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-print"></i> Print
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Sales Invoice</a>
                            <a class="dropdown-item" href="#">Collection Receipt</a>
                            <a class="dropdown-item" href="#">Delivery Receipt</a>
                        </div>
                    </div>
                    <div class="dropdown float-right mr-2">
                        <button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-edit"></i> Mark As
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item btn-mark-as-overdue" href="#">Overdue</a>
                            <a class="dropdown-item btn-mark-as-partially-completed" href="#">Partially Completed</a>
                            <a class="dropdown-item btn-mark-as-completed" href="#">Completed</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!--   <div class="mb-4 pull-left">
                        <h6>BBBOOTSTRAP.COM</h6>
                        <ul class="list list-unstyled mb-0 text-left">
                            <li>2269 Six Sigma</li>
                            <li>New york city</li>
                            <li>+1 474 44737 47 </li>
                        </ul>
                    </div>-->
                </div>

                <div class="col-12 d-md-flex flex-md-wrap">
                    <div class="mb-4 ml-auto"> <span class="text-muted">Order Details:</span>
                        <div class="d-flex flex-wrap wmin-md-400">
                            <ul class="list list-unstyled mb-0 text-left">
                                <li>Shipment ID:</li>
                                <li>Order ID:</li>
                                <li>Order type:</li>
                                <li>Date:</li>
                                <li>Status:</li>
                            </ul>
                            <ul class="list list-unstyled text-right mb-0 ml-auto">
                                <li><span class="font-weight-semibold" id="shipmentId"></span></li>
                                <li><span class="font-weight-semibold" id="orderId"></span>
                                <li id="orderSource"></li>
                                </li>
                                <li><span id="dateTimeSubmittedIso"></span></li>
                                <li><span class="font-weight-semibold" id="status"></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-md-flex flex-md-wrap">
                    <div class="mb-4 mb-md-2 text-left"> <span class="text-muted">Customer Info:</span>
                        <ul class="list list-unstyled mb-0">
                            <li>
                                <h5 class="my-2" id="custName"></h5>
                            </li>
                            <li id="shipCity"></li>
                            <li id="shipState"></li>
                            <li id="shipZip"></li>
                            <li id="shipPhone"></li>
                            <li><a href="#" id="customerEmail"></a></li>
                        </ul>
                    </div>
                    <div class="mb-2 ml-auto"> <span class="text-muted">Payment Details:</span>
                        <div class="d-flex flex-wrap wmin-md-400">
                            <ul class="list list-unstyled mb-0 text-left">
                                <li>Shipping Charge Amount:</li>
                                <li>Shipping Tax Total Amount:</li>
                                <li>Package Total:</li>
                                <li>
                                    <h5 class="my-2">Total Due:</h5>
                                </li>
                            </ul>
                            <ul class="list list-unstyled text-right mb-0 ml-auto">
                                <li><span class="font-weight-semibold" id="shippingChargeAmount"></span></li>
                                <li><span class="font-weight-semibold" id="shippingTaxTotalAmount"></span>
                                <li id="packageTotal"></li>
                                </li>
                                <li>
                                    <h5 class="font-weight-semibold my-2">₱<span id="salesTaxAmount"></span></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h4>Line Items</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">SKU</th>
                                <th scope="col">Description</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Status</th>
                                <th scope="col" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-pickup-details">

                        </tbody>
                    </table>
                </div>
            </div>
            <form id="pickup-form" class="modal-footer" method="POST">
                @csrf
                <select class="form-control float-right" style="width:200px" name="hub_id" required>
                    <option value="" disabled selected>Choose hub</option>
                    @foreach ($hubs as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary" id="btn-pickedup" type="submit">Tag All as
                    Picked Up</button>
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
