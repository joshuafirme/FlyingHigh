@section('title', 'Product')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Stock Adjustment Report</h4>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4 mt-2">
                                <form class="form-inline" action="{{ route('filterStockAdjustment') }}"
                                    method="get">
                                    <div class="form-group mr-4">
                                        <label>Date from</label>
                                        <input type="date" class="form-control ml-2" name="date_from"
                                            value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d') }}"
                                            required>
                                    </div>
                                    <div class="form-group mr-4">
                                        <label>Date to</label>
                                        <input type="date" class="form-control ml-2" name="date_to"
                                            value="{{ isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d') }}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                </form>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Qty Adjusted</th>
                                            <th scope="col">Ramarks</th>
                                            <th scope="col">Date time adjusted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($stock_adjustments))
                                            @foreach ($stock_adjustments as $item)
                                                <tr>
                                                    <td>{{ $item->sku }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->action }}</td>
                                                    <td>{{ $item->qty_adjusted }}</td>
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

@include('reports.stock-adjustment.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('reports.stock-adjustment.script')
