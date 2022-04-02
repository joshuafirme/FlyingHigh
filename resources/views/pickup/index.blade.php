@section('title', 'Pick-up')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Pick-up</a></li>
                            <li class="breadcrumb-item active">Pick-up List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Buffer Stock</th>
                                        <th scope="col">JTE lot code</th>
                                        <th scope="col">Supplier lot code</th>
                                        <th scope="col">Expiration</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($pickups))
                                        @foreach ($pickups as $item)
                                            <tr id="record-id-{{ $item->id }}">
                                                <td>{{ $item->sku }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->stock }}</td>
                                                <td>{{ $item->buffer_stock }}</td>
                                                <td>{{ $item->jde_lot_code ? $item->jde_lot_code : 'N/A' }}</td>
                                                <td>{{ $item->supplier_lot_code ? $item->supplier_lot_code : 'N/A' }}
                                                <td>{{ $item->expiration }}</td>
                                                <td>{{ Utils::formatDate($item->created_at) }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown"
                                                            role="button" aria-haspopup="true" aria-expanded="false"><i
                                                                class="fas fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu">
                                                            <a class="btn dropdown-item btn-pickup"
                                                                data-target="#pickupModal" data-toggle="modal"
                                                                data-sku="{{ $item->sku }}"
                                                                data-desc="{{ $item->description }}"><i
                                                                    class="fa fa-exchange-alt"></i> Pick-ups</a>
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

@include('hubs-inventory.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('hubs-inventory.script')
