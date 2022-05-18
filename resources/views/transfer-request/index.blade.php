@section('title', 'Transfer Request | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Transfer Request</h4>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4 mt-2 d-md-flex flex-md-wrap">
                                <div class="form-group ml-auto mt-4 mt-sm-2">

                                    <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#importModal"><i
                                            class="fas fa-file-import"></i>
                                        Import Excel
                                    </a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/product-lot-codes/export/') }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/product-lot-codes/download/') }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/product-lot-codes/preview/') }}"
                                        target="_blank"><i class="fa fa-print"></i> Print</a>
                                </div>
                                <form action="{{ route('searchProduct') }}" method="get" class="ml-4">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="key" style="width: 280px;"
                                            placeholder="Search"
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
                                            <th scope="col">Qty</th>
                                            <th scope="col">External line no</th>
                                            <th scope="col">UOM</th>
                                            <th scope="col">Order date</th>
                                            <th scope="col">Delivery date</th>
                                            <th scope="col">Remarks</th>
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
                                                    <td>{{ $item->external_line_no }}</td>
                                                    <td>{{ $item->uom }}</td>
                                                    <td>{{ $item->order_date }}</td>
                                                    <td>{{ $item->delivery_date }}</td>
                                                    <td>{{ $item->remarks }}</td>
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

@include('transfer-request.modals')

@include('layouts.footer')

@include('scripts._global_scripts')
