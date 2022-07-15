@section('title', 'Inbound Transfer Report | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Reports</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Reports</a></li>
                            <li class="breadcrumb-item active">Inbound Transfer</li>
                        </ol>
                    </div>
                </div>
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
                                <form class="form-inline" action="{{ url('/reports/inbound-transfer/filter') }}"
                                    method="get">
                                    <div class="form-group mr-4 col-12 col-md-auto">
                                        <label>Date </label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_from"
                                            value="{{ $date_from }}" required>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_to"
                                            value="{{ $date_to }}" required>
                                    </div>
                                    <div class="form-group m-auto">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                    <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ url('/reports/inbound-transfer') }}"><i class="fa fa-sync"
                                                aria-hidden="true"></i> Refresh</a>
                                    </div>
                                </form>

                                <div class="form-group ml-auto mt-4 mt-sm-2">
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/inbound-transfer/export/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/inbound-transfer/download/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/inbound-transfer/preview/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fa fa-print"></i> Print</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Transaction Reference #</th>
                                            <th scope="col">Order #</th>
                                            <th scope="col">Order Type</th>
                                            <th scope="col">Order Date</th>
                                            <th scope="col">Vendo No</th>
                                            <th scope="col">Vendor Name</th>
                                            <th scope="col">Ship From Address</th>
                                            <th scope="col">Ship From Country</th>
                                            <th scope="col">Date time Received</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($purchase_orders))
                                            @foreach ($purchase_orders as $item)
                                                <tr>
                                                    <td>{{ $item->transactionReferenceNumber }}</td>
                                                    <td>{{ $item->orderNumber }}</td>
                                                    <td>{{ $item->orderType }}</td>
                                                    <td>{{ $item->orderDate }}</td>
                                                    <td>{{ $item->vendorNo }}</td>
                                                    <td>{{ $item->vendorName }}</td>
                                                    <td>{{ $item->shipFromAddress }}</td>
                                                    <td>{{ $item->shipFromCountry }}</td>
                                                    <td>{{ Utils::formatDate($item->receiptDate) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('/stock-transfer/asn/' . $item->orderNumber) }}"
                                                            target="_blank" class="btn btn-primary btn-sm btn-transfer"
                                                            data-obj="{{ json_encode($item) }}">
                                                            Line Items</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11">
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
                                echo $purchase_orders->appends(request()->query())->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('reports.inbound-transfer.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('reports.inbound-transfer.script')
