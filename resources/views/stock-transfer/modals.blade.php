<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ url('/stock-transfer/import') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Import Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">

                <div class="col-md-12 mt-3">
                    <label class="form-label">Tracking #</label>
                    <input type="text" name="tracking_no" class="form-control" required>
                </div>
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
</div>

<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <form id="transferForm" action="{{ url('/stock-transfer/transfer') }}" method="POST"
            enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <input type="hidden" name="id">
                <div class="col-md-12 mt-1">
                    <label class="form-label">Tracking #</label>
                    <input type="text" name="tracking_no" class="form-control" readonly>
                </div>
                <div class="col-md-12 mt-1">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Pending Qty</label>
                    <input type="text" name="qty_order" class="form-control" readonly>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Qty To Transfer</label>
                    <input type="number" name="qty_transfer" class="form-control" required>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="select-from-old" name="old_lot_code">
                        <label class="form-check-label" for="exampleCheck1">Select from the inventory</label>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Lot Code</label>
                    <input type="text" name="lot_code" class="form-control">
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-label">Expiration</label>
                    <input type="date" name="expiration" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit">Transfer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="receiveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="receiveForm" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <table width="100%" cellpadding="0" cellspaceing="0">
                        <tbody>
                            <tr>
                                <td>
                                    <address class="ic-invoice-addess">
                                        <strong>Order Number:
                                            <span class="orderNumber"></span></strong><br>
                                        <p class="mb-0">Transaction Reference #:
                                            <span class="transactionReferenceNumber"></span>
                                        </p>
                                        <p class="mb-0">Order Type:
                                            <span class="orderType"></span>
                                        </p>
                                        <p class="mb-0">Order Date:
                                            <span class="orderDate"></span>
                                        </p>
                                        <p class="mb-0">Vendor no:
                                            <span class="vendorNo"></span>
                                        </p>
                                        <p class="mb-0">Vendor name:
                                            <span class="vendorName"></span>
                                        </p>
                                        <p class="mb-0">Ship from address:
                                            <span class="shipFromAddress"></span>
                                        </p>
                                        <p class="mb-0">Ship from country:
                                            <span class="shipFromCountry"></span>
                                        </p>
                                    </address>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="orderNumber" class="form-control">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Receive date</label>
                    <input type="datetime-local" name="receiveDate" class="form-control" value="{{ date('Y-m-d') }}"
                        required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary btn-confirm-receive" type="submit">Receive</button>
                <a class="btn btn-sm btn-outline-dark">Close</a>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Send Confirmation to YL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">

                <div class="col-md-12 mt-3">
                    <label class="col-form-label">Transaction Reference #</label>
                    <select class="form-control" id="transactionReferenceNumber"
                        placeholder="Search Transaction Reference #" required>
                        @if (isset($po_transaction) && count($po_transaction) > 0)
                            @foreach ($po_transaction as $item)
                                <option value="{{ $item->transactionReferenceNumber }}">
                                    {{ $item->transactionReferenceNumber }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <table class="table pb-3">
                        <thead>
                            <th scope="col">Order #</th>
                            <th scope="col">Order Type</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Vendo No</th>
                            <th scope="col">Vendor Name</th>
                            <th scope="col">Ship From Address</th>
                            <th scope="col">Ship From Country</th>
                        </thead>
                        <tbody id="orderListContainer">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" id="btn-send-confirmation" type="submit">Send</button>
            </div>
        </div>
    </div>
</div>
