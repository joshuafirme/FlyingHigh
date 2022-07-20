@section('title', 'Product')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<style>
    .tbl-product-details tr td {
        padding: 2px !important;
        margin: 2px !important;
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Products</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Warehouse</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">

                                @include('layouts.alerts')
                                <div class="font-weight-normal mb-2">
                                    <div>
                                        <span>Total SKU Count:</span> <span class="font-weight-bold">{{ Utils::numFormat($product_count) }}</span>
                                    </div>
                                    <div>
                                        Last synced: <span id="last_synced"></span>
                                    </div>
                                </div>
                                <div class="d-md-flex flex-md-wrap ml-2">
                                    <div class="row d-flex mb-3 mb-sm-3">
                                        <button type="button" id="btn-create"
                                            class="btn btn-sm btn-primary w-auto open-modal m-1 col-12 col-sm-auto"
                                            modal-type="create">
                                            <i class="fa fa-plus"></i> Create
                                        </button>
                                        <!-- <button type="button"
                                            class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            id="btn-open-import-via-barcode" data-backdrop="static"
                                            data-keyboard="false"><i class="fa fa-barcode"></i>
                                            Import Stock via Barcode
                                        </button>-->
                                        <button type="button"
                                            class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            data-toggle="modal" data-target="#importModal"><i
                                                class="fas fa-file-import"></i>
                                            Import Excel
                                        </button>
                                        <a class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            href="{{ url('/product/export') }}" target="_blank"><i
                                                class="fas fa-file-export"></i> Export Excel</a>
                                        <!--<button type="button"
                                            class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            data-toggle="modal" data-target="#apiModal" data-backdrop="static"
                                            data-keyboard="false"><i class="fas fa-box-open"></i>
                                            Stock Transfer
                                        </button>-->
                                        <button type="button"
                                            class="btn btn-sm btn-primary btn-bulk-transfer w-autos m-1 col-12 col-sm-auto"
                                            data-toggle="modal" data-target="#bulkTransferModal" data-backdrop="static"
                                            data-keyboard="false"><i class="fa fa-exchange-alt"></i>
                                            Hub Transfer
                                        </button>
                                        <button class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            id="btn-sync-skumaster">
                                            <i class="fa fa-sync"></i>
                                            Sync SKU Master
                                        </button>
                                        <div class="dropdown float-left m-1">
                                            <button class="btn btn-sm btn-primary dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 Stock Status
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" target="_blank"
                                                    href="#">Send Stock Status Report</a>
                                                <a class="dropdown-item" target="_blank"
                                                    href="#">Print Stock Status Report</a>
                                            </div>
                                        </div>

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
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Base UOM</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Buffer Stock</th>
                                            <th scope="col">Stock Level</th>
                                            <th scope="col" style="width:20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products))
                                            @foreach ($products as $item)
                                                @php
                                                    $text_class = 'text-success';
                                                    $stock_level = 'Normal';
                                                    $icon = '<i class="fas fa-check-circle"></i>';
                                                    $stock = $lot_code->getAllStock($item->itemNumber, $item->baseUOM);
                                                    if ($stock == 0) {
                                                        $text_class = 'text-danger';
                                                        $stock_level = 'Out of stock';
                                                        $icon = '<i class="fas fa-exclamation-circle"></i>';
                                                    } elseif ($stock <= $item->buffer_stock) {
                                                        $text_class = 'text-warning';
                                                        $stock_level = 'Critical';
                                                        $icon = '<i class="fas fa-exclamation-circle"></i>';
                                                    }
                                                    $date_now = date('Y-m-d');
                                                @endphp
                                                <tr id="{{ $item->id }}">
                                                    <td><a class="btn-view-detail" href="#"
                                                            data-target="#detailModal" data-toggle="modal"
                                                            data-info="{{ json_encode($item) }}">
                                                            {{ $item->itemNumber }}</a></td>
                                                    <td>{{ $item->productDescription }}</td>
                                                    <td>{{ $item->baseUOM }}</td>
                                                    <td class="{{ $text_class }}">{{ $stock }}</td>
                                                    <td>{{ $item->bufferStock }}</td>
                                                    <td class="{{ $text_class }}">{!! $icon !!}
                                                        {{ $stock_level }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary open-modal"
                                                            modal-type="update" data-info="{{ $item }}"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"
                                                            data-desc="{{ $item->productDescription }}"><i
                                                                class="fa fa-edit"></i></a>
                                                        <a class="btn btn-sm btn-primary btn-view-detail"
                                                            data-toggle="tooltip" data-placement="top" title="Product Details / Lot Codes"
                                                            data-info="{{ json_encode($item) }}">
                                                            <i class="fa fa-eye"></i></a>
                                                        <a class="btn btn-sm btn-primary btn-stock-adjustment"
                                                            data-toggle="tooltip" data-placement="top" title="Stock Adjustment"
                                                            data-sku="{{ $item->itemNumber }}"
                                                            data-stock="{{ $stock }}"
                                                            data-desc="{{ $item->productDescription }}"
                                                            data-backdrop="static" data-keyboard="false"><i
                                                                class="fas fa-sort-amount-up"></i></i></a>
                                                        <a class="btn btn-sm btn-primary btn-hubs-stock"
                                                            data-toggle="tooltip" data-placement="top" title="Hub Stocks"
                                                            data-itemNumber="{{ $item->itemNumber }}"
                                                            data-desc="{{ $item->productDescription }}"><i
                                                                class="fa fa-warehouse"></i></a>
                                                        <a class="btn btn-sm btn-danger btn-delete"
                                                            data-toggle="tooltip" data-placement="top" title="Delete"
                                                            data-id="{{ $item->id }}">
                                                            <i class="fa fa-trash"></i></a>

                                                        <!--<a href="#" class="btn btn-dark btn-sm"
                                                                data-toggle="dropdown" data-backdrop="static"
                                                                data-keyboard="false" role="button" aria-haspopup="true"
                                                                aria-expanded="false"><i
                                                                    class="fas fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu">
                                                                 <a class="btn dropdown-item btn-transfer"
                                                                    data-backdrop="static" data-keyboard="false"
                                                                    data-target="#transferModal" data-toggle="modal"
                                                                    data-itemNumber="{{ $item->itemNumber }}"
                                                                    data-desc="{{ $item->productDescription }}"
                                                                    data-stock="{{ $stock }}"><i
                                                                        class="fa fa-exchange-alt"></i> Hub Transfer</a>-->
                            </div>
                            </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
