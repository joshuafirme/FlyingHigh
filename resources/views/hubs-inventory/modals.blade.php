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
                <div class="col-md-6">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Barcode</label>
                    <input type="text" class="form-control" name="barcode" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Buffer Stock</label>
                    <input type="number" class="form-control" name="buffer_stock" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">JTE lot code</label>
                    <input type="text" class="form-control" name="jde_lot_code" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Supplier lot code</label>
                    <input type="text" class="form-control" name="supplier_lot_code" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Expiration</label>
                    <input type="date" class="form-control" name="expiration" readonly>
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control" name="status" readonly>
                </div>
                <div id="bundle-qty-container" class="col-sm-12 col-md-12 mt-4 d-none">
                    <label for="">Bundle Stocks</label>
                    <table class="table table-striped pb-3">
                        <thead>
                            <th>Sku</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="tbl-bundle-qty">
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
