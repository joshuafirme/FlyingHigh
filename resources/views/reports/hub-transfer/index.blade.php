@section('title', 'Hub Transfer Report | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Reports</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Reports</a></li>
                            <li class="breadcrumb-item active">Hub Transfer</li>
                        </ol>
                    </div>
                </div>
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
                                <form class="form-inline" action="{{ route('filterHubTransfer') }}"
                                    method="get">
                                    <div class="form-group mr-4 col-12 col-md-auto">
                                        <label>Date from</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_from"
                                            value="{{ $date_from }}" required>
                                    </div>
                                    <div class="form-group mr-4 col-12 col-md-auto">
                                        <label>Date to</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_to"
                                            value="{{ $date_to }}" required>
                                    </div>
                                    <div class="form-group ml-1">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                      <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary" href="{{ url('/reports/hub-transfer') }}"><i class="fa fa-sync" aria-hidden="true"></i> Refresh</a>
                                    </div>
                                </form>

                                <div class="form-group ml-auto mt-4 mt-sm-2">
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/hub-transfer/export/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/hub-transfer/download/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/hub-transfer/preview/' . $date_from . '/' . $date_to) }}"
                                        target="_blank"><i class="fa fa-print"></i> Print</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Hub</th>
                                            <th scope="col">Qty Transferred</th>
                                            <th scope="col">Date time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($hub_transfer))
                                            @foreach ($hub_transfer as $item)
                                                <tr>
                                                    <td>{{ $item->sku }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->hub }}</td>
                                                    <td>{{ $item->qty_transferred }}</td>
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
                                echo $hub_transfer->links('pagination::bootstrap-4');
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
