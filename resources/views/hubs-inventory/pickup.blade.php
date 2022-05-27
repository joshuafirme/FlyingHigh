@section('title', 'Hub')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Pickup</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/hubs/' . $receiver) }}"
                                    class="ic-javascriptVoid">Hub</a></li>
                            <li class="breadcrumb-item active">{{ $hub_name }}</li>
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
                                        <h4><strong>Order Details</strong></h4>
                                    </div>
                                    <hr>
                                    <div>
                                        <div class="ic-responsive-invoice">
                                            @if ($order_details)
                                                <table width="100%" cellpadding="0" cellspaceing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <address class="ic-invoice-addess"><strong>Customer
                                                                        Info:</strong><br>
                                                                    <p class="mb-0">
                                                                        {{ $order_details->custName }}</p>
                                                                    <p class="mb-0">
                                                                        {{ $order_details->customerEmail }}</p>
                                                                    <p class="mb-0"></p>
                                                                    <p class="mb-0">
                                                                        Mallory,
                                                                        Hillary</p>
                                                                    <p class="mb-0">Cocos (Keeling) Islands
                                                                    </p>
                                                                </address>
                                                            </td>
                                                            <td>
                                                                <address class="ic-invoice-addess"><strong>Shipped
                                                                        To:</strong>
                                                                    <p class="mb-0"></p>
                                                                    <p class="mb-0"></p>
                                                                    <p class="mb-0"></p>
                                                                    <p class="mb-0"></p>
                                                                    <p class="mb-0"></p>
                                                                </address>
                                                            </td>
                                                            <td>
                                                                <address class="ic-invoice-addess ic-right-content">
                                                                    <strong>Invoice:</strong>
                                                                    <p class="mb-0">Invoice ID # 00000072</p>
                                                                    <p class="mb-0">Date: 2021-12-18
                                                                    </p>
                                                                    <p class="mb-0">Total: $1380.00</p>
                                                                    <p class="mb-0">Status: Partially Paid</p>
                                                                </address>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2"><strong>Line Items</strong></div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Order ID</th>
                                                    <th scope="col">SKU</th>
                                                    <th scope="col">Lot Number</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Qty Ordered</th>
                                                    <th scope="col">Qty Shipped</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($line_items))
                                                    @foreach ($line_items as $item)
                                                        <tr>
                                                            <td>{{ $item->orderId }}</td>
                                                            <td>{{ $item->partNumber }}</td>
                                                            <td>{{ $item->lotNumber ? $item->lotNumber : '-' }}</td>
                                                            <td>{{ $item->description }}</td>
                                                            <td>{{ $item->qtyOrdered }}</td>
                                                            <td>{{ $item->qtyShipped }}</td>
                                                            <td>
                                                                <a class="btn btn-sm btn-primary"
                                                                    href="{{ url('/hubs/' . $receiver . '/pickup/' . $item->shipmentId) }}"
                                                                    target="_blank">Pickup
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
