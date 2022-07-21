@section('title', 'Inventory | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Inventory</h4>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4 mt-2 d-md-flex flex-md-wrap">
                                <div class="form-group ml-auto mt-4 mt-sm-2">
                                    <a class="btn btn-sm btn-primary" href="{{ url('/inventory/export/') }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/inventory/download/') }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary" href="{{ url('/inventory/preview/') }}"
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
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Lot Code</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Unit of Measure</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Buffer Stock</th>
                                            <th scope="col">Stock Level</th>
                                            <th scope="col">Expiration</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products))
                                            @foreach ($products as $item)
                                                @php
                                                    $text_class = 'text-success';
                                                    $stock_level = 'Normal';
                                                    $icon = '<i class="fas fa-check-circle"></i>';
                                                    if ($item->stock == 0) {
                                                        $text_class = 'text-danger';
                                                        $stock_level = 'Out of stock';
                                                        $icon = '<i class="fas fa-exclamation-circle"></i>';
                                                    } elseif ($item->stock <= $item->buffer_stock) {
                                                        $text_class = 'text-warning';
                                                        $stock_level = 'Critical';
                                                        $icon = '<i class="fas fa-exclamation-circle"></i>';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->sku }}</td>
                                                    <td>{{ $item->lot_code ? $item->lot_code : 'N/A' }}</td>
                                                    <td>{{ $item->productDescription }}</td>
                                                    <td>{{ $item->uom }}</td>
                                                    <td>{{ $item->stock }}</td>
                                                    <td>{{ $item->bufferStock }}</td>
                                                    <td class="{{ $text_class }}">{!! $icon !!}
                                                        {{ $stock_level }}</td>
                                                    <td>{{ $item->expiration ? Utils::formatDate($item->expiration, false) : 'N/A' }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10">
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
