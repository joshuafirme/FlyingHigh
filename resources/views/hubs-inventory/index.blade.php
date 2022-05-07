@section('title', 'Hub')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4>Hub</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Hub</a></li>
                            <li class="breadcrumb-item active">{{ $hub_name }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4 mt-2 d-md-flex flex-md-wrap">

                                 <div class="ml-auto">
                                        <form action="{{ url('/hubs/'.$hub_id.'/search') }}" method="get">
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
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Lot Code</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Expiration Date</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Date time Last Transferred</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($products))
                                            @foreach ($products as $item)
                                                @php
                                                    $expiration = $hub_inv->getExpiration($item->lot_code);
                                                @endphp
                                                <tr id="record-id-{{ $item->id }}">
                                                    <td>{{ $item->sku }}</td>
                                                    <td>{{ $item->lot_code ? $item->lot_code : 'N/A' }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $expiration ? $expiration : 'N/A' }}</td>
                                                    <td>{{ $item->stock }}</td>
                                                    <td>{{ Utils::formatDate($item->updated_at) }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="#" class="btn btn-dark btn-sm"
                                                                data-toggle="dropdown" role="button"
                                                                aria-haspopup="true" aria-expanded="false"><i
                                                                    class="fas fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu">
                                                                <a class="btn dropdown-item btn-view-detail"
                                                                    data-target="#detailModal" data-toggle="modal"
                                                                    data-info="{{ json_encode($item) }}"><i
                                                                        class="fa fa-eye"></i> View Details</a>
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

@include('hubs-inventory.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('hubs-inventory.script')
