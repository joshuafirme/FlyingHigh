@section('title', 'Stock Transfer')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Stock Transfer</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Stock Transfer</li>
                            <li class="breadcrumb-item active"><a href="#" class="ic-javascriptVoid">Purchase
                                    Order</a></li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h4><strong>Purchase Order</strong></h4>
                                    </div>
                                    <hr>
                                    <div>
                                        <div class="ic-responsive-invoice">
                                            <table width="100%" cellpadding="0" cellspaceing="0">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <address class="ic-invoice-addess">
                                                                <strong>Order Number:
                                                                    {{ $purchase_order->orderNumber }}</strong><br>
                                                                <p class="mb-0">Transaction Reference #:
                                                                    {{ $purchase_order->transactionReferenceNumber }}
                                                                </p>
                                                                <p class="mb-0">Order Type:
                                                                    {{ $purchase_order->orderType }}</p>
                                                                <p class="mb-0">Order Date:
                                                                    {{ $purchase_order->orderDate }}</p>
                                                                <p class="mb-0">Vendor no:
                                                                    {{ $purchase_order->vendorNo }}</p>
                                                                <p class="mb-0">Vendor name:
                                                                    {{ $purchase_order->vendorName }}</p>
                                                                <p class="mb-0">Ship from address:
                                                                    {{ $purchase_order->shipFromAddress }}</p>
                                                                <p class="mb-0">Ship from country:
                                                                    {{ $purchase_order->shipFromCountry }}</p>
                                                            </address>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2"><strong>Line Items</strong></div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Action</th>
                                                    <th scope="col">Line Number</th>
                                                    <th scope="col">Transaction Action</th>
                                                    <th scope="col">Item Number</th>
                                                    <th scope="col">Lot Number</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Qty Ordered</th>
                                                    <th scope="col">Qty Open</th>
                                                    <th scope="col">Ship Date</th>
                                                    <th scope="col">UOM</th>
                                                    <th scope="col">Location</th>
                                                    <th scope="col">Country Of Origin</th>
                                                    <th scope="col">Expected Date</th>
                                                    <th scope="col">extWeight</th>
                                                    <th scope="col">wtUom</th>
                                                    <th scope="col">Hold Code</th>
                                                    <th scope="col">Vendor Lot No</th>
                                                    <th scope="col">lotExp</th>
                                                    <th scope="col">iOfLading</th>
                                                    <th scope="col">Ship Method</th>
                                                    <th scope="col">Carrier Name</th>
                                                    <th scope="col">PalletId</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($line_items as $item)
                                                    <tr>
                                                        <td>
                                                        <a class="btn btn-primary btn-sm btn-transfer" data-obj="{{ json_encode($item) }}">
                                                        <i class="fas fa-dolly"></i> Transfer</a>
                                                        </td>
                                                        <td>{{ $item->lineNumber }}</td>
                                                        <td>{{ $item->transactionAction }}</td>
                                                        <td>{{ $item->itemNumber }}</td>
                                                        <td>{{ $item->lotNumber }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td>{{ $item->quantityOrdered }}</td>
                                                        <td>{{ $item->quantityOpen }}</td>
                                                        <td>{{ $item->shipDate }}</td>
                                                        <td>{{ $item->unitOfMeasure }}</td>
                                                        <td>{{ $item->location }}</td>
                                                        <td>{{ $item->countryOfOrigin }}</td>
                                                        <td>{{ $item->expectedDate }}</td>
                                                        <td>{{ $item->extWeight }}</td>
                                                        <td>{{ $item->wtUom }}</td>
                                                        <td>{{ $item->holdCode }}</td>
                                                        <td>{{ $item->vendorLotNo }}</td>
                                                        <td>{{ $item->lotExp }}</td>
                                                        <td>{{ $item->iOfLading }}</td>
                                                        <td>{{ $item->shipMethod }}</td>
                                                        <td>{{ $item->carrierName }}</td>
                                                        <td>{{ $item->palletId }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('hubs-inventory.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('hubs-inventory.script')
