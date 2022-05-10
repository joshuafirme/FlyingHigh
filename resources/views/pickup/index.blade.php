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
                <h4 class="text-dark">Orders</h4>
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
                                                placeholder="Search by Shipment ID or Order ID"
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
                                            <th scope="col">OrderID</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Date time submitted</th>
                                            @if ($status == 0 || $status == 2)
                                                <th>Status</th>
                                            @elseif ($status == 3)
                                                <th>Reason</th>
                                            @endif
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($pickups))
                                            @foreach ($pickups as $item)
                                                <tr>
                                                    <td>{{ $item->shipmentId }}</td>
                                                    <td>{{ $item->orderId }}</td>
                                                    <td>
                                                        {!! $item->custName . '<br>' . '<a href="mailto:' . $item->customerEmail . '">' . $item->customerEmail . '</a>' !!}
                                                    </td>
                                                    <td>{{ $item->dateTimeSubmittedIso }}</td>
                                                    @php
                                                        $status = json_decode(Utils::getStatusTextClass($item->status));
                                                    @endphp
                                                    <td><span
                                                            class="badge badge-pill badge-{{ $status->class }}">{{ $status->text }}</span>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary btn-pickup-details"
                                                            data-target="#pickupModal" data-toggle="modal"
                                                            data-orderId="{{ $item->orderId }}"
                                                            data-order-details="{{ json_encode($item) }}"><i
                                                                class="fa fa-eye"></i> Order details</a>
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
                                echo $pickups->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('pickup.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('pickup.script')
