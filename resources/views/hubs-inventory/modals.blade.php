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
                <div class="col-md-12">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Barcode</label>
                    <input type="text" class="form-control" name="barcode" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control" name="status" readonly>
                </div>
                <div class="col-sm-12 col-md-12 mt-4">
                    <table class="table table-hover pb-3">
                        <thead>
                            <th>JDE Lot Code</th>
                            <th>Stock</th>
                            <th>Expiration</th>
                        </thead>
                        <tbody class="tbl-lot-codes">
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
