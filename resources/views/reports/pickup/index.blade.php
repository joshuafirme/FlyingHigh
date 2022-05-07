@section('title', 'Pickup Report | Flying High')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')
@php
    $status_obj = json_decode(Utils::getStatusTextClass($status));
@endphp
<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="text-dark">Reports</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Reports</a></li>
                            <li class="breadcrumb-item active">Pickup</li>
                            <li class="breadcrumb-item active">{{ $status_title ? $status_title : $status_obj->text }}</li>

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
                                <form class="form-inline" action="{{ url('/reports/pickup/filter') }}"
                                    method="get">
                                    <div class="form-group mr-4 col-12 col-md-auto">
                                        <label>Status</label>
                                        <select class="form-control ml-0 ml-sm-2" name="status" required>
                                            <option value="0" {{ $status == 0 ? 'selected' : '' }}>For Pick Up
                                            </option>
                                            <option value="1" {{ $status == 1 ? 'selected' : '' }}>Completed</option>
                                            <option value="2" {{ $status == 2 ? 'selected' : '' }}>Overdue</option>
                                            <option value="3" {{ $status == 3 ? 'selected' : '' }}>Partially Completed</option>
                                        </select>
                                    </div>
                                    <div class="form-group mr-4 col-12 col-md-auto">
                                        <label>Date </label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_from"
                                            value="{{ $date_from }}" required>
                                        <label> - </label>
                                        <input type="date" class="form-control ml-0 ml-sm-2" name="date_to"
                                            value="{{ $date_to }}" required>
                                    </div>
                                    <div class="form-group m-auto">
                                        <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                                    </div>
                                    <div class="form-group ml-1">
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ url('/reports/pickup/for-pickup') }}"><i class="fa fa-sync"
                                                aria-hidden="true"></i> Refresh</a>
                                    </div>
                                </form>

                                <div class="form-group ml-auto mt-4 mt-sm-2">
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/pickup/export/' . $date_from . '/' . $date_to . '/' . $status) }}"
                                        target="_blank"><i class="fas fa-file-export"></i> Export Excel</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/pickup/download/' . $date_from . '/' . $date_to . '/' . $status) }}"
                                        target="_blank"><i class="fa fa-download"></i> Download PDF</a>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ url('/reports/pickup/preview/' . $date_from . '/' . $date_to . '/' . $status) }}"
                                        target="_blank"><i class="fa fa-print"></i> Print</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Shipment Id</th>
                                            <th scope="col">OrderID</th>
                                            <th scope="col">BatchID</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Date time submitted</th>
                                            @if (request()->status == 0 || request()->status == 2)
                                                <th>Status</th>
                                            @elseif (request()->status == 3)
                                                <th>Reason</th>
                                            @endif
                                            <th>Action</th>
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
                                                    @if ($status == 0 || $status == 2)
                                                        <td>
                                                            @if ($item->status == 0)
                                                                <span
                                                                    class="badge badge-pill badge-primary">Unclaimed</span>
                                                            @elseif ($item->status == 2)
                                                                <span
                                                                    class="badge badge-pill badge-warning">Overdue</span>
                                                            @endif
                                                        </td>
                                                    @elseif ($status == 3)
                                                        <td>{{ $item->reason }}</td>
                                                    @endif
                                                    <td><a class="btn btn-outline-primary btn-pickup-details"
                                                            data-target="#pickupModal" data-toggle="modal"
                                                            data-orderId="{{ $item->orderId }}"
                                                            data-order-details="{{ json_encode($item) }}"> View Details</a></td>
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
                                echo $pickups->appends(request()->query())->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('reports.pickup.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('reports.pickup.script')

