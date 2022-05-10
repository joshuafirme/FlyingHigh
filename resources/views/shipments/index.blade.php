@section('title', 'For Pick-up')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')
@php
$status = request()->status;
@endphp
<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Shipment</h4>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-3 mb-3">
                                <div class="float-right">
                                    <form action="{{ url('/pickup/' . $status . '/search') }}" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="key" style="width: 280px;"
                                                placeholder="Search by Shipment ID"
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
                                            <th scope="col">Ship Carrier</th>
                                            <th scope="col">Ship Method</th>
                                            <th scope="col">Total Weight</th>
                                            <th scope="col">Freight Charges</th>
                                            <th scope="col">Qty Packages</th>
                                            <th scope="col">CurrCode</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($shipments))
                                            @foreach ($shipments as $item)
                                                <tr>
                                                    <td>{{ $item->shipmentId }}</td>
                                                    <td>{{ $item->shipCarrier }}</td>
                                                    <td>{{ $item->shipMethod }}</td>
                                                    <td>{{ $item->totalWeight . " " . $item->weightUoM }}</td>
                                                    <td>{{ $item->freightCharges }}</td>
                                                    <td>{{ $item->qtyPackages }}</td>
                                                    <td>{{ $item->currCode }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary btn-pickup-details"
                                                            data-target="#pickupModal" data-toggle="modal"
                                                            data-shipmentId="{{ $item->shipmentId }}"
                                                            data-order-details="{{ json_encode($item) }}"><i
                                                                class="fa fa-eye"></i> Shipment details</a>
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
                                echo $shipments->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('shipments.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('shipments.script')
