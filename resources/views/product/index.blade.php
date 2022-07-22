@section('title', 'SKU Master')
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
                <h4 class="page-title">SKU Master</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Warehouse</a></li>
                            <li class="breadcrumb-item active">SKU Master</li>
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
                                        <span>Total SKU Count:</span> <span
                                            class="font-weight-bold">{{ Utils::numFormat($product_count) }}</span>
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
                                        <button class="btn btn-sm btn-primary w-autos m-1 col-12 col-sm-auto"
                                            id="btn-sync-skumaster">
                                            <i class="fa fa-sync"></i>
                                            Sync SKU Master
                                        </button>

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
                                            <th scope="col">Created</th>
                                            <th scope="col">Modified</th>
                                            <th scope="col" style="width:20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products))
                                            @foreach ($products as $item)
                                                @php
                                                    $date_now = date('Y-m-d');
                                                @endphp
                                                <tr id="{{ $item->id }}">
                                                    <td><a class="btn-view-detail" href="#"
                                                            data-target="#detailModal" data-toggle="modal"
                                                            data-info="{{ json_encode($item) }}">
                                                            {{ $item->itemNumber }}</a></td>
                                                    <td>{{ $item->productDescription }}</td>
                                                    <td>{{ $item->baseUOM }}</td>
                                                    <td>{{ Utils::formatDate($item->created_at) }}</td>
                                                    <td>{{ Utils::formatDate($item->updated_at) }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-outline-primary open-modal"
                                                            modal-type="update" data-info="{{ $item }}"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"
                                                            data-desc="{{ $item->productDescription }}"><i
                                                                class="fa fa-edit"></i></a>
                                                        <a class="btn btn-sm btn-outline-primary btn-view-detail"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Product Details"
                                                            data-info="{{ json_encode($item) }}">
                                                            <i class="fa fa-eye"></i></a>
                                                        <a class="btn btn-sm btn-outline-danger btn-delete"
                                                            data-toggle="tooltip" data-placement="top" title="Delete"
                                                            data-id="{{ $item->id }}">
                                                            <i class="fa fa-trash"></i></a>

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
