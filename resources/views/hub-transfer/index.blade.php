@section('title', 'Hub Transfer')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Hub Transfers</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Warehouse</a></li>
                            <li class="breadcrumb-item active">Hub Transfers</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">

                                @include('layouts.alerts')

                                <div class="d-md-flex flex-md-wrap ml-2">

                                    <div class="ml-auto">
                                        <form action="{{ url('/hub-transfer/search') }}" method="get">
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

                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-right" style="width:10%;">Action</th>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Lot Code</th>
                                            <th scope="col">Base UOM</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Pallet ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products))
                                            @foreach ($products as $item)
                                                <tr id="{{ $item->id }}">
                                                    <td class="text-right">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary w-auto open-modal m-1 btn-add"
                                                            data-id="{{ $item->id }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ $item->sku }} <br> {{ $item->productDescription }}</td>
                                                    <td>
                                                        {{ $item->lot_code ? $item->lot_code : 'N/A' }} <br>
                                                        <span class="text-muted">Lot Exp: {{ $item->expiration ? $item->expiration : 'N/A' }}</span>
                                                    </td>
                                                    <td>{{ $item->uom }}</td>
                                                    <td>{{ $item->stock }}</td>
                                                    <td>{{ $item->palletId }}</td>
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
                                echo $products->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h5>Transfer List</h5>
                            <div class="table-responsive">
                                <form id="transfer-form" accept="#">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">SKU</th>
                                                <th scope="col">Lot Code</th>
                                                <th scope="col">Stock</th>
                                                <th scope="col">Qty to Transfer</th>
                                                <th scope="col" style="width:20%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl_transfer_list">

                                        </tbody>
                                    </table>
                            </div>

                            <label for="validationCustom04" class="form-label mt-3">Hub</label>
                            <select class="form-control" name="hub_id" id="hub" required>
                                <option selected disabled value="">Choose...</option>
                                @foreach ($hubs as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>


                            <button type="submit" class="btn btn-sm btn-primary w-auto mt-3">
                                Transfer
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('hub-transfer.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('hub-transfer.script')
