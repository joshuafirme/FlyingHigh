@section('title', 'Transactions')
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
                <h4 class="page-title">Transactions</h4>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Transaction Reference #</th>
                                            <th scope="col">transactionType</th>
                                            <th scope="col">Data Count</th>
                                            <th scope="col">Sender</th>
                                            <th scope="col">Receiver</th>
                                            <th scope="col">Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($transactions))
                                            @foreach ($transactions as $item)
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            <u>{{ $item->transactionReferenceNumber }}</u>
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->transactionType }}</td>
                                                    <td>{{ $item->messageCount }}</td>
                                                    <td>{{ $item->sender }}</td>
                                                    <td>{{ $item->receiver }}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6">
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
                                echo $transactions->appends(request()->query())->links('pagination::bootstrap-4');
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

