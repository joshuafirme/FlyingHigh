@section('title', 'Stock Adjustment Report | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Reports</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Reports</a></li>
                            <li class="breadcrumb-item active">Stock Adjustment</li>
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
                                    $remarks_id = request()->remarks_id;
                                @endphp
                                <form class="form-inline" action="{{ route('filterStockAdjustment') }}"
                                    method="get">
                                    <div class="form-group mr-3 col-12 col-md-auto">
                                        <label>Date from</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_from"
                                            value="{{ $date_from }}" required>
                                    </div>
                                    <div class="form-group mr-3 col-12 col-md-auto">
                                        <label>Date to</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_to"
                                            value="{{ $date_to }}" required>
                                    </div>
                                    <div class="form-group ml-3 col-12 col-md-auto">
                                        <label>Remarks</label>
                                        <select class="form-control ml-0 ml-sm-2" name="remarks_id" required>
                                            <option disabled selected>Select a remarks</option>
                                            @foreach ($adjustment_remarks as $item)
                                                @php
                                                    $selected = $item->id == request()->remarks_id ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $item->id }}" {{ $selected }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group m-auto">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                    <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ url('/reports/stock-adjustment') }}"><i class="fa fa-sync"
                                                aria-hidden="true"></i> Refresh</a>
                                    </div>
                                </form>

                                <div class="form-group ml-auto mt-4 mt-sm-2">
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/stock-adjustment/export/' . $date_from . '/' . $date_to . '/' . $remarks_id) }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/stock-adjustment/download/' . $date_from . '/' . $date_to . '/' . $remarks_id) }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/stock-adjustment/preview/' . $date_from . '/' . $date_to . '/' . $remarks_id) }}"
                                        target="_blank"><i class="fa fa-print"></i> Print</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Lot Code</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Qty Adjusted</th>
                                            <th scope="col">Adjusted by</th>
                                            <th scope="col">Remarks</th>
                                            <th scope="col">Date time adjusted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($stock_adjustments))
                                            @foreach ($stock_adjustments as $item)
                                                <tr>
                                                    <td>{{ $item->sku }}</td>
                                                    <td>{{ $item->lot_code ? $item->lot_code : 'N/A' }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->action }}</td>
                                                    <td>{{ $item->qty_adjusted }}</td>
                                                    <td>{{ $item->adjusted_by }}</td>
                                                    <td>{{ $item->remarks }}</td>
                                                    <td>{{ Utils::formatDate($item->created_at) }}</td>
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
                                echo $stock_adjustments->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('layouts.footer')

@include('scripts._global_scripts')
