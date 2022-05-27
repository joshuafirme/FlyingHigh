@section('title', 'Hub')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Hub</h4>
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
                                @php
                                    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d');
                                    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
                                    $hub_id = request()->hub_id;
                                @endphp
                                <form class="form-inline" action="{{ route('filterHubTransfer') }}" method="get">
                                    <div class="form-group col-12 col-md-auto">
                                        <label>Transferred from</label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_from"
                                            value="{{ $date_from }}" required>
                                    </div>
                                    <div class="form-group mr-4 col-12 col-md-auto">
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_to"
                                            value="{{ $date_to }}" required>
                                    </div>
                                    <div class="form-group ml-1">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                    <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary" href="{{ url('/reports/hub-transfer') }}"><i
                                                class="fa fa-sync" aria-hidden="true"></i> Refresh</a>
                                    </div>
                                </form>
                                <div class="ml-auto">
                                    <form action="{{ url('/hubs/' . $receiver . '/search') }}" method="get">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="key" style="width: 280px;"
                                                placeholder="Search Tracking # or Shipment ID"
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
                                            <th scope="col">Shipment Id</th>
                                            <th scope="col">Tracking #</th>
                                            <th scope="col">Ship Carrier</th>
                                            <th scope="col">Ship Method</th>
                                            <th scope="col">Total Weight</th>
                                            <th scope="col">Freight Charges</th>
                                            <th scope="col">Qty Packages</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($deliveries))
                                            @foreach ($deliveries as $item)
                                                @php
                                                    $expiration = $hub_inv->getExpiration($item->lot_code);
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->shipmentId }}</td>
                                                    <td>
                                                        <a href="https://app.flyinghighenergyexpress.com/catalog/tracking/view/{{ $item->trackingNo }}"
                                                            target="_blank"><u>{{ $item->trackingNo }}</u></a>
                                                    </td>
                                                    <td>{{ $item->shipCarrier }}</td>
                                                    <td>{{ $item->shipMethod }}</td>
                                                    <td>{{ $item->totalWeight . ' ' . $item->weightUoM }}</td>
                                                    <td>{{ $item->freightCharges . ' ' . $item->currCode }}</td>
                                                    <td>{{ $item->qtyPackages }}</td>
                                                    <td>
                                                        @if ($item->status == 0)
                                                            <span class="badge badge-pill badge-primary">Pending</span>
                                                        @elseif ($item->status == 1)
                                                            <span class="badge badge-pill badge-warning">Picked up</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary"
                                                            href="https://app.flyinghighenergyexpress.com/catalog/tracking/view/{{ $item->trackingNo }}"
                                                            target="_blank">Shipment History >
                                                        </a>
                                                        <a class="btn btn-sm btn-primary"
                                                            href="{{ url('/hubs/' . $receiver . '/pickup/' . $item->shipmentId) }}"
                                                            target="_blank">Pickup >
                                                        </a>
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
                                echo $deliveries->links('pagination::bootstrap-4');
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
