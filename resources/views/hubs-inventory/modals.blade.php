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
