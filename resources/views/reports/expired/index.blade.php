@section('title', 'Expired List | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Expired List</h4>
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
                                <form class="form-inline" action="{{ url('/reports/expired/filter') }}"
                                    method="get">
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
                                        <a class="btn btn-sm btn-primary" href="{{ url('/reports/expired') }}"><i class="fa fa-sync" aria-hidden="true"></i> Refresh</a>
                                    </div>
                                </form>
                                <div class="form-group ml-auto mt-4 mt-sm-2">
                                    <a class="btn btn-sm btn-primary" href="{{ url('/reports/expired/export/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/reports/expired/download/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/reports/expired/preview/' . $date_from . '/' . $date_to) }}"
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
                                            <th scope="col">Qty</th>
                                            <th scope="col">Expiration</th>
                                            <th scope="col">Condition</th>
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
