<!-- Pickup modal -->
<div class="modal fade" id="pickupModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <h4 class="page-title">Line Items</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Line Number</th>
                                <th scope="col">Item Number</th>
                                <th scope="col">Qty Rcd Good</th>
                                <th scope="col">Qty Rcd Bad</th>
                                <th scope="col">Bill of Lading</th>
                                <th scope="col">Unit Of Measure</th>
                                <th scope="col">Location</th>
                                <th scope="col">Lot Number</th>
                                <th scope="col">Lot Expiration</th>
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
