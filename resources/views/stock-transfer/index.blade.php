@section('title', 'Stock Transfer | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Stock Transfer</h4>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4 mt-2 d-md-flex flex-md-wrap">
                                @php
                                    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d');
                                    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
                                    $hub_id = request()->hub_id;
                                @endphp
                                <div class="form-group ml-auto mt-4 mt-sm-2">
                                    <a class="btn btn-sm btn-primary"><i class="fas fa-sync"></i> Sync ASN</a>
                                    <a data-target="#confirmModal" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fas fa-paper-plane"></i> Send Confirmation to YL</a>

                                </div>
                                <form action="{{ url('/stock-transfer/search') }}" method="get" class="ml-4">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="key" style="width: 280px;"
                                            placeholder="Search Transaction Reference #"
                                            value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @include('layouts.alerts')
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
                                            <th scope="col">Status</th>
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
                                                    <td>
                                                        @if ($item->status == 1)
                                                            <span class="badge badge-pill badge-success">Received</span>
                                                        @else
                                                            <span class="badge badge-pill badge-info">Pending</span>
                                                        @endif
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

@include('stock-transfer.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('stock-transfer.script')
