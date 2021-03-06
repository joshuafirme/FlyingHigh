<!-- Pickup modal -->
<div class="modal fade" id="pickupModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Pick Up Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <!-- <div class="col-12">
                    <a href="#" class="btn btn-sm btn-primary section-print-btn float-right m-1"><i
                            class="fa fa-print"></i>
                        <span>Print</span></a>
                    <a href="#" class="btn btn-sm btn-success float-right m-1"><i class="fa fa-download"></i>
                        <span>Download</span></a>
                </div>-->
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
                <div class="col-sm-6">
                    <div class="mb-4 ">
                        <div class="text-sm-right">
                            <h4 class="invoice-color mb-2 mt-md-2">Shipment ID: <span id="shipmentId"></span></h4>
                            <h5 class="invoice-color mb-2 mt-md-2">Order ID: <span id="orderId"></span></h5>
                            <ul class="list list-unstyled mb-0">
                                <li>Date: <span class="font-weight-semibold" id="dateTimeSubmittedIso"></span></li>
                                <li>Due date: <span class="font-weight-semibold" id="contractDate"></span>
                                </li>
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
                                    <h5 class="font-weight-semibold my-2">???<span id="salesTaxAmount"></span></h5>
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
                                <th scope="col">SKU</th>
                                <th scope="col">Description</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-pickup-details">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- End Pickup modal -->