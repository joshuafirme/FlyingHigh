@section('title', 'Stock Transfer | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Stock Transfer</h4>
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
                                <form class="form-inline" action="{{ url('/stock-transfer/filter') }}" method="get">
                                    <div class="form-group col-12 col-md-auto">
                                        <label>Delivery Date from</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_from"
                                            value="{{ $date_from }}" required>
                                    </div>
                                    <div class="form-group col-12 col-md-auto">-</div>
                                    <div class="form-group mr-4 col-12 col-md-auto">
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_to"
                                            value="{{ $date_to }}" required>
                                    </div>
                                    <div class="form-group ml-1">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                    <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary" href="{{ url('/stock-transfer') }}"><i
                                                class="fa fa-sync" aria-hidden="true"></i> Refresh</a>
                                    </div>
                                </form>

                                <div class="form-group ml-auto mt-4 mt-sm-2">

                                    <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#importModal"><i
                                            class="fas fa-file-import"></i>
                                        Import Excel
                                    </a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/stock-transfer/export/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/stock-transfer/download/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/stock-transfer/preview/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fa fa-print"></i> Print</a>
                                </div>
                                <form action="{{ url('/stock-transfer/search') }}" method="get"
                                    class="ml-4">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="key" style="width: 280px;"
                                            placeholder="Search Tracking #"
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
                                            <th scope="col">Tracking #</th>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Pending Qty</th>
                                            <th scope="col">Qty Received</th>
                                            <th scope="col">UOM</th>
                                            <th scope="col">Order date</th>
                                            <th scope="col">Delivery date</th>
                                            <th scope="col">Remarks</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($transfer_request))
                                            @foreach ($transfer_request as $item)
                                                <tr>
                                                    <td>{{ $item->tracking_no }}</td>
                                                    <td>{{ $item->sku }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->qty_order }}</td>
                                                    <td>{{ $item->qty_received }}</td>
                                                    <td>{{ $item->uom }}</td>
                                                    <td>{{ $item->order_date }}</td>
                                                    <td>{{ $item->delivery_date }}</td>
                                                    <td>{{ $item->remarks }}</td>
                                                    <td>
                                                        @if ($item->status == 0)
                                                            <span class="badge badge-primary badge-pill">Pending</span>
                                                        @elseif ($item->status == 1)
                                                            <span class="badge badge-warning badge-pill">Partially
                                                                Transferred</span>
                                                        @elseif ($item->status == 2)
                                                            <span
                                                                class="badge badge-success badge-pill">Transferred</span>
                                                        @endif
                                                    </td>
                                                    @if ($item->status != 2)
                                                        <td>
                                                            <a class="btn btn-primary btn-sm btn-transfer"
                                                                data-obj="{{ json_encode($item) }}"><i
                                                                    class="fas fa-dolly"></i> Transfer</a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11">
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

                            @php
                                echo $transfer_request->appends(request()->query())->links('pagination::bootstrap-4');
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
