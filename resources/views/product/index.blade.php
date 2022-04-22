@section('title', 'Product')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4>Product</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Product</a></li>
                            <li class="breadcrumb-item active">Product List <span
                                    class="badge badge-pill badge-primary">{{ $product_count }}</span></li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-3 mb-3">

                                @include('layouts.alerts')
                                <div class="d-md-flex flex-md-wrap">
                                    <div class="row d-flex mb-3 mb-sm-3">
                                        <button type="button" id="btn-create"
                                            class="btn btn-sm btn-primary w-auto open-modal m-1 col-12 col-sm-auto"
                                            modal-type="create">
                                            <i class="fa fa-plus"></i> Create
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            id="btn-open-import-via-barcode" data-backdrop="static"
                                            data-keyboard="false"><i class="fa fa-barcode"></i>
                                            Import Stock via Barcode
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            data-toggle="modal" data-target="#apiModal" data-backdrop="static"
                                            data-keyboard="false"><i class="fa fa-code"></i>
                                            Import Stock via API
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            data-toggle="modal" data-target="#importModal"><i
                                                class="fas fa-file-import"></i>
                                            Import Excel
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-primary btn-bulk-transfer w-autos m-1 col-12 col-sm-auto"
                                            data-toggle="modal" data-target="#bulkTransferModal" data-backdrop="static"
                                            data-keyboard="false"><i class="fa fa-exchange-alt"></i>
                                            Hub Transfer
                                        </button>
                                        <a href="{{ url('/product') }}"
                                            class="btn btn-sm btn-primary btn-bulk-transfer w-autos m-1 col-12 col-sm-auto">
                                            <i class="fa fa-sync"></i>
                                            Refresh
                                        </a>

                                    </div>

                                    <div class="ml-auto">
                                        <form action="{{ route('searchProduct') }}" method="get">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="key"
                                                    style="width: 280px;" placeholder="Search by SKU or Description"
                                                    value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" style="min-height: 500px;">
                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Buffer Stock</th>
                                            <th scope="col">Stock Level</th>
                                            <th scope="col">Expiration</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Created at</th>
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
                                                    if ($item->qty == 0) {
                                                        $text_class = 'text-danger';
                                                        $stock_level = 'Out of stock';
                                                        $icon = '<i class="fas fa-exclamation-circle"></i>';
                                                    } elseif ($item->qty <= $item->buffer_stock) {
                                                        $text_class = 'text-warning';
                                                        $stock_level = 'Critical';
                                                        $icon = '<i class="fas fa-exclamation-circle"></i>';
                                                    }
                                                    $expiration = Utils::validateExpiration($item->expiration);
                                                    $date_now = date('Y-m-d');
                                                    $item_condition = '';
                                                    if ($expiration != 'N/A' && $date_now > $expiration) {
                                                        $item_condition = 'Expired';
                                                    }
                                                @endphp
                                                <tr id="record-id-{{ $item->id }}">
                                                    <td><a class="btn-view-detail" href="#" data-target="#detailModal"
                                                            data-toggle="modal" data-info="{{ json_encode($item) }}">
                                                            {{ $item->sku }}</a></td>
                                                    <td>{{ $item->description }}</td>
                                                    <td class="{{ $text_class }}">{{ $item->qty }}</td>
                                                    <td>{{ $item->buffer_stock }}</td>
                                                    <td class="{{ $text_class }}">{!! $icon !!}
                                                        {{ $stock_level }}</td>
                                                    <td>
                                                        {{ $expiration }} <br>
                                                        <span class="text-danger">{{ $item_condition }}</span>
                                                    </td>
                                                    <td>@php
                                                        if ($item->status == 1) {
                                                            echo '<span class="badge rounded-pill bg-primary">Active</span>';
                                                        } elseif ($item->status == 0) {
                                                            echo '<span class="badge rounded-pill bg-danger">Inactive</span>';
                                                        }
                                                    @endphp</td>
                                                    <td>{{ Utils::formatDate($item->created_at) }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="#" class="btn btn-dark btn-sm"
                                                                data-toggle="dropdown" data-backdrop="static"
                                                                data-keyboard="false" role="button" aria-haspopup="true"
                                                                aria-expanded="false"><i
                                                                    class="fas fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu">
                                                                <a class="btn dropdown-item btn-view-detail"
                                                                    data-target="#detailModal" data-toggle="modal"
                                                                    data-info="{{ json_encode($item) }}"><i
                                                                        class="fa fa-eye"></i> View Details</a>
                                                                <a class="btn dropdown-item btn-transfer"
                                                                    data-backdrop="static" data-keyboard="false"
                                                                    data-target="#transferModal" data-toggle="modal"
                                                                    data-sku="{{ $item->sku }}"
                                                                    data-desc="{{ $item->description }}"><i
                                                                        class="fa fa-exchange-alt"></i> Hub Transfer</a>
                                                                <a class="btn dropdown-item btn-hubs-stock"
                                                                    data-target="#hubsStockModal" data-toggle="modal"
                                                                    data-sku="{{ $item->sku }}"
                                                                    data-desc="{{ $item->description }}"><i
                                                                        class="fa fa-warehouse"></i> Hubs
                                                                    Stock</a>
                                                                <a class="btn btn-stock-adjustment dropdown-item"
                                                                    data-target="#stockAdjustmentModal"
                                                                    data-toggle="modal" data-sku="{{ $item->sku }}"
                                                                    data-desc="{{ $item->description }}"
                                                                    data-backdrop="static" data-keyboard="false"><i
                                                                        class="fas fa-sort-amount-up"></i></i> Stock
                                                                    Adjustment</a>
                                                                <a class="btn btn-edit open-modal dropdown-item"
                                                                    data-backdrop="static" data-keyboard="false"
                                                                    modal-type="update"
                                                                    data-info="{{ json_encode($item) }}"><i
                                                                        class="fa fa-edit"></i> Edit</a>
                                                                <a class="btn delete-record dropdown-item"
                                                                    data-id="{{ $item->id }}" object="product"
                                                                    data-toggle="modal"
                                                                    data-target="#delete-record-modal">
                                                                    <i class="fa fa-trash">
                                                                        Delete</i>
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </td>
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
                                echo $products->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('product.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('product.script')
