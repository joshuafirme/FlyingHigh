@section('title', 'Hub')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

@php
$order = new App\Models\Order();
$invoice = new App\Models\Invoice();
@endphp

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Hub</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Hub</a></li>
                            <li class="breadcrumb-item active">{{ $hub_name }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $tab = isset($_GET['tab']) ? $_GET['tab'] : "shipments";
                            @endphp
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == "shipments" ? "active" : "" }}"
                                        href="{{ url("/hubs/$receiver?tab=shipments") }}">Shipments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == "inventory" ? "active" : "" }}"
                                        href="{{ url("/hubs/$receiver?tab=inventory") }}">Inventory</a>
                                </li>
                            </ul>


                            @if ( ! isset($_GET['tab']) || $_GET['tab'] == "shipments")

                                <div class="mb-4 mt-2 d-md-flex flex-md-wrap">
                                    <div class="mt-3 mb-3">
                                        <button type="button" id="btn-create"
                                            class="btn btn-sm btn-primary w-auto open-modal" data-toggle="modal"
                                            data-target="#addPickupModal">
                                            Add Shipment
                                        </button>
                                    </div>

                                    <div class="ml-auto mt-4 mt-sm-2">

                                        <form action="{{ url("/hubs/{$receiver}/shipment") }}" method="get">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="key"
                                                    style="width: 280px;"
                                                    placeholder="Search by Shipment ID or Tracking #"
                                                    value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Shipment Id</th>
                                                <th scope="col">Tracking #</th>
                                                <th scope="col">Ship Carrier</th>
                                                <th scope="col">Ship Method</th>
                                                <th scope="col">Total Weight</th>
                                                <th scope="col">Freight Charges</th>
                                                <th scope="col">Qty Packages</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($shipments))
                                                @foreach ($shipments as $item)
                                                    <tr>
                                                        <td>{{ $item->shipmentId }}</td>
                                                        <td>
                                                            <a href="https://app.flyinghighenergyexpress.com/catalog/tracking/view/{{ $item->trackingNo }}"
                                                                target="_blank"><u>{{ $item->trackingNo }}</u></a>
                                                        </td>
                                                        <td>{{ $item->shipCarrier }}</td>
                                                        <td>{{ $item->shipMethod }}</td>
                                                        <td>{{ $item->totalWeight . ' ' . $item->weightUoM }}</td>
                                                        <td>{{ $item->freightCharges . ' ' . $item->currCode }}
                                                        </td>
                                                        <td>{{ $item->qtyPackages }}</td>
                                                        <td>
                                                            @if ($item->status == 0)
                                                                <span
                                                                    class="badge badge-pill badge-primary">Pending</span>
                                                            @elseif ($item->status == 1)
                                                                <span class="badge badge-pill badge-warning">Picked
                                                                    up</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $order_details = $order->getOrderDetails($item->shipmentId);
                                                            @endphp
                                                            <div class="dropdown float-left m-1" data-toggle="tooltip"
                                                                data-placement="top" title="Print Invoice / Receipt">
                                                                <button
                                                                    class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="fa fa-print"></i> Print
                                                                </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton"
                                                                    style="z-index: 99999">
                                                                    @foreach ($invoice->getInvoiceDetails($item->shipmentId) as $item)
                                                                        @php
                                                                            $invoice_name = 'Sales Invoice';
                                                                            if ($item->invoiceType == 3) {
                                                                                $invoice_name = 'Delivery Receipt';
                                                                            } elseif ($item->invoiceType == 4) {
                                                                                $invoice_name = 'Collection Receipt';
                                                                            }
                                                                        @endphp
                                                                        <a class="dropdown-item" target="_blank"
                                                                            href="{{ url('/order/generate/' . $item->shipmentId . '/' . $item->orderId . '?type=' . $item->invoiceType . '&invoice_no=' . $item->invoiceDetail) }}">{{ $invoice_name }}</a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <a class="btn btn-sm btn-outline-primary float-left m-1"
                                                                href="{{ url('/hubs/' . $receiver . '/pickup/' . $item->shipmentId) }}"
                                                                target="_blank">Pickup >
                                                            </a>
                                                            <a class="btn btn-sm btn-outline-danger float-left m-1"
                                                                href="#">Cancel
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10">
                                                        <div class="alert alert-danger alert-dismissible fade show"
                                                            role="alert">
                                                            No shipment found.
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                    @php
                                        echo $shipments->appends(request()->query())->links('pagination::bootstrap-4');
                                    @endphp
                                </div>
                            @else
                                <div class="table-responsive mt-3">
                                    <table class="table table-borderless table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">SKU</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Bin location</th>
                                                <th scope="col">Lot Code</th>
                                                <th scope="col">Expiration</th>
                                                <th scope="col">Stock</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($inventory))
                                                @foreach ($inventory as $item)
                                                    @php
                                                        $expiration = $hub_inv->getExpiration($item->lot_code);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $item->itemNumber }}</td>
                                                        <td>{{ $item->productDescription }}</td>
                                                        <td>{{ $item->bin }}</td>
                                                        <td>{{ $item->lot_code }}</td>
                                                        <td>{{ $expiration }}</td>
                                                        <td>{{ $item->stock }}</td>
                                                        <td></td>

                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10">
                                                        <div class="alert alert-danger alert-dismissible fade show"
                                                            role="alert">
                                                            No data found.
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                    @php
                                        echo $inventory->appends(request()->query())->links('pagination::bootstrap-4');
                                    @endphp
                                </div>

                            @endif


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
