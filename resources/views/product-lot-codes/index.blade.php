@section('title', 'Lot Code List | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Lot Code List</h4>
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
                                    <div class="form-group col-12 col-md-auto">
                                        <label>Status</label>
                                        <select class="form-control ml-0 ml-sm-2" name="remarks_id" required>
                                            <option disabled selected>Select a remarks</option>
                                        </select>
                                    </div>
                                    <div class="form-group m-auto">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                    <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ url('/product-lot-codes') }}"><i class="fa fa-sync"
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
                                            <th scope="col">Stock</th>
                                            <th scope="col">Expiration</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products))
                                            @foreach ($products as $item)
                                                <tr>
                                                    <td>{{ $item->sku }}</td>
                                                    <td>{{ $item->lot_code ? $item->lot_code : 'N/A' }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->stock }}</td>
                                                    <td>{{ $item->expiration ? $item->expiration : 'N/A' }}</td>
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
                            </div>

                            @php
                                echo $products->appends(request()->query())->links('pagination::bootstrap-4');
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
