@section('title', 'For Pick-up')
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
                            <li class="breadcrumb-item active">For Pick-up List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-3 mb-3">
                                <div class="float-right">
                                    <form action="{{ route('searchPickup') }}" method="get">
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
                            <table class="table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Shipment Id</th>
                                        <th scope="col">OrderID</th>
                                        <th scope="col">BatchID</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Date time submitted</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($pickups))
                                        @foreach ($pickups as $item)
                                            <tr>
                                                <td>{{ $item->shipmentId }}</td>
                                                <td>{{ $item->orderId }}</td>
                                                <td>{{ $item->batchId }}</td>
                                                <td>
                                                    {!! $item->custName . '<br>' . '<a href="mailto:' . $item->customerEmail . '">' . $item->customerEmail . '</a>' !!}
                                                </td>
                                                <td>{{ $item->dateTimeSubmittedIso }}</td>
                                                <td>
                                                    @if ($item->status == 0)
                                                        <span class="badge badge-pill badge-primary">Unclaimed</span>
                                                    @elseif ($item->status == 2)
                                                        <span class="badge badge-pill badge-warning">Overdue</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown"
                                                            role="button" aria-haspopup="true" aria-expanded="false"><i
                                                                class="fas fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu">
                                                            <a class="btn dropdown-item btn-pickup-details"
                                                                data-target="#pickupModal" data-toggle="modal"
                                                                data-orderId="{{ $item->orderId }}"
                                                                data-order-details="{{ json_encode($item) }}"><i
                                                                    class="fa fa-exchange-alt"></i> Pickup Details</a>
                                                            @if ($item->status == 1)
                                                                <a class="btn dropdown-item btn-tag-as-overdue"
                                                                    data-shipmentId="{{ $item->shipmentId }}"><i
                                                                        class=" fa fa-clock"></i> Tag as Overdue</a>
                                                            @endif
                                                            <a class="
                                                                    btn dropdown-item"
                                                                data-orderId="{{ $item->orderId }}"><i
                                                                    class="fa fa-print"></i> Generate Receipt</a>
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
