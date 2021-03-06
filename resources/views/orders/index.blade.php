@section('title', 'For Pick-up')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')
@php
$status = request()->status;
@endphp
<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Orders</h4>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4 mt-2 d-md-flex flex-md-wrap">
                                @php
                                    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d');
                                    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
                                @endphp
                                <form class="form-inline" action="{{ url('/orders/filter/'.$branch_id) }}" method="get">
                                    <div class="form-group mr-2 col-12 col-md-auto">
                                        <label>Date from</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_from"
                                            value="{{ $date_from }}" required>
                                    </div>
                                    <div class="form-group mr-2 col-12 col-md-auto">
                                        <label>Date to</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_to"
                                            value="{{ $date_to }}" required>
                                    </div>
                                    <div class="form-group ml-1">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                    <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary" href="{{ url('/orders') }}"><i
                                                class="fa fa-sync" aria-hidden="true"></i> Refresh</a>
                                    </div>
                                    <div class="form-group ml-1">
                                        <button class="btn btn-sm btn-primary" id="btn-show-fetch-modal"><i
                                                class="fa fa-download" aria-hidden="true"></i> Fetch Orders</button>
                                    </div>
                                </form>
                                <div class="ml-auto mt-4 mt-sm-2">

                                    <form action="{{ url('/orders/search/'.$branch_id) }}" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="key"
                                                style="width: 370px;" placeholder="Search by Shipment ID, Order ID or Customer Email"
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
                                            <th>Shipment Id</th>
                                            <th>OrderID</th>
                                            <th>Customer</th>
                                            <th>Order Source</th>
                                            <th class="text-right">Package Total</th>
                                            <th>Date time submitted</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($orders))
                                            @foreach ($orders as $item)
                                                <tr>
                                                    <td>
                                                        <a href="#" class="shipmentId-txt" data-toggle="tooltip" data-placement="top" title="Copy"
                                                            data-id="{{ $item->shipmentId }}">
                                                            <u>{{ $item->shipmentId }}</u>
                                                        </a>
                                                        <input type="hidden" id="{{ $item->shipmentId }}"
                                                            value="{{ $item->shipmentId }}">
                                                    </td>
                                                    <td>{{ $item->orderId }}</td>
                                                    <td>
                                                        {!! $item->custName . '<br>' . '<a href="mailto:' . $item->customerEmail . '">' . $item->customerEmail . '</a>' !!}
                                                    </td>
                                                    <td>{{ $item->orderSource }}</td>
                                                    <td class="text-right">{{ $item->packageTotal }}</td>
                                                    <td>{{ $item->dateTimeSubmittedIso }}</td>
                                                    <td>
                                                        <div class="dropdown float-left m-1">
                                                            <button
                                                                class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="fa fa-print"></i> Print
                                                            </button>
                                                            <div class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton">
                                                                @foreach ($invoice->getInvoiceDetails($item->shipmentId) as $invoice)
                                                                    @php
                                                                        $invoice_name = 'Sales Invoice';
                                                                        if ($invoice->invoiceType == 3) {
                                                                            $invoice_name = 'Delivery Receipt';
                                                                        } elseif ($invoice->invoiceType == 4) {
                                                                            $invoice_name = 'Collection Receipt';
                                                                        }
                                                                    @endphp
                                                                    <a class="dropdown-item" target="_blank"
                                                                        href="{{ url('/order/generate/' . $item->shipmentId . '/' . $item->orderId . '?type=' . $invoice->invoiceType . '&invoice_no=' . $invoice->invoiceDetail) }}">{{ $invoice_name }}</a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        
                                                            <a class="btn btn-sm btn-outline-primary float-left m-1"
                                                                href='{{ url("/order/pickup/$branch_id/$item->shipmentId") }}'
                                                                target="_blank">Pickup >
                                                            </a>
                                                            <a class="btn btn-sm btn-outline-danger float-left m-1 btn-cancel"
                                                                data-shipmentId="{{ $item->shipmentId }}"
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
                                                        No data found.
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
                            </div>
                            @php
                                echo $orders->appends(request()->query())->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('orders.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('orders.script')
