@section('title', 'Hub')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Pick up</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Hub</li>
                            <li class="breadcrumb-item active"><a href="{{ url('/hubs/1001/pickup/' . $receiver) }}" class="ic-javascriptVoid">{{ $hub_name }}</a></li>      
                            <li class="breadcrumb-item">Pick up</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($order_details)

                                <div class="row">
                                    <div class="col-12">
                                        <div>
                                            <div class="dropdown float-right m-1">
                                                <button class="btn btn-sm btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-print"></i> Print
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                    style="z-index: 99999">
                                                    @foreach ($invoice->getInvoiceDetails($order_details->shipmentId) as $item)
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
                                            <h4><strong>Order Details</strong></h4>
                                        </div>
                                        <hr>
                                        <div>
                                            <div class="ic-responsive-invoice">
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
                                                                    <p class="mb-0">TIN:
                                                                        {{ Utils::separateString($order_details->customerTIN) }}
                                                                    </p>
                                                                    <p class="mb-0">Shipping Address:
                                                                        {{ $order_details->shipAddr1 }}</p>
                                                                    <p class="mb-0">Phone:
                                                                        {{ $order_details->shipPhone }}</p>
                                                                    </p>
                                                                </address>
                                                            </td>
                                                            <td>
                                                                <address class="ic-invoice-addess ic-right-content">
                                                                    @php
                                                                        $total_amount_due = 0;
                                                                    @endphp
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <th>Payment Method
                                                                            </th>
                                                                            <th>Amount</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($order_line_items as $item)
                                                                                @if ($item->lineType == 'PN')
                                                                                    @php
                                                                                        $total_amount_due = $total_amount_due + $item->itemUnitPrice;
                                                                                    @endphp
                                                                                    <tr>
                                                                                        <td>
                                                                                            {{ $item->name }}
                                                                                        </td>
                                                                                        <td class="text-right">
                                                                                            ₱
                                                                                            {{ number_format((float) str_replace('-', '', $item->itemUnitPrice), 2, '.', ',') }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                        </tbody>
                            @endforeach
                            </table>
                            <p class="mb-0">Shipping Charge Amount:
                                ₱ {{ number_format((float) $order_details->shippingChargeAmount, 2, '.', '') }}
                            </p>
                            <p class="mb-0">Sales Tax Amount:
                                ₱ {{ number_format((float) $order_details->salesTaxAmount, 2, '.', '') }}</p>
                            <p class="mb-0">Shipping Tax Total Amount:
                                ₱ {{ number_format((float) $order_details->shippingTaxTotalAmount, 2, '.', '') }}
                            </p>
                            <p class="mb-0">Package Total:
                                ₱ {{ number_format((float) $order_details->packageTotal, 2, '.', '') }}
                            </p>
                            </address>
                            </td>
                            <td>
                                <address class="ic-invoice-addess ic-right-content">
                                    <p class="mb-0">Shipment ID:
                                        {{ $order_details->shipmentId }}</p>
                                    <p class="mb-0">Order Type:
                                        {{ $order_details->orderSource }}</p>
                                    <p class="mb-0">Ship Method:
                                        {{ $order_details->shipMethod }}</p>
                                    <p class="mb-0">Ship Carrier:
                                        {{ $order_details->shipCarrier ? $order_details->shipCarrier : 'N/A' }}
                                    </p>
                                    <p class="mb-0">Date Time Submitted:
                                        {{ $order_details->dateTimeSubmittedIso }}
                                    </p>
                                    <p class="mb-0">Status: <span
                                            class="badge badge-info badge-pill">Pending</span>
                                    </p>
                                </address>
                            </td>
                            </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="p-2"><strong>Line Items</strong></div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Lot Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Qty Ordered</th>
                                    <th scope="col">Qty Shipped</th>
                                    <th scope="col">Ship Date Time</th>
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
                                            <td>{{ $item->shipDateTime }}</td>
                                            <td>
                                                @if ($item->status == 0)
                                                    <a class="btn btn-sm btn-primary btn-pickup"
                                                     data-orderId="{{ $item->orderId }}" data-partNumber="{{ $item->partNumber }}" >Pickup
                                                    </a>
                                                @elseif ($item->status == 1)
                                                    <span class="badge badge-pill badge-success">Picked up</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10">
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
