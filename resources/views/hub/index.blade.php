@section('title', 'Hub')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Maintenance</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Maintenance</a></li>
                            <li class="breadcrumb-item active">Branch Plant</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-3 mb-3">

                                @include('layouts.alerts')

                                <div>
                                    <button type="button" id="btn-create"
                                        class="btn btn-sm btn-primary w-auto open-modal" modal-type="create">
                                        Add Branch Plant <i class="bi bi-plus"></i>
                                    </button>
                                </div>

                                <div class="float-right">
                                    <form action="{{ route('searchHub') }}" method="get">
                                        <div class="input-group mb-3">
                                            <input type="text" name="key" class="form-control" placeholder="Search"
                                                aria-label="Search" required>
                                            <button class="btn btn-outline-secondary" type="submit"><i
                                                    class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Branch Plant #</th>
                                            <th scope="col">Branch Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($hubs))
                                            @foreach ($hubs as $item)
                                                <tr id="record-id-{{ $item->id }}">
                                                    <td>{{ $item->receiver }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->phone }}</td>
                                                    <td>{{ $item->address }}</td>
                                                    <td>@php
                                                        if ($item->status == 1) {
                                                            echo '<span class="badge rounded-pill bg-success">Active</span>';
                                                        } elseif ($item->status == 0) {
                                                            echo '<span class="badge rounded-pill bg-danger">Inactive</span>';
                                                        }
                                                    @endphp</td>
                                                    <td>
                                                        <a class="btn btn-edit open-modal" modal-type="update"
                                                            data-info="{{ json_encode($item) }} "><i
                                                                class="fa fa-edit"></i></a>
                                                        @if ($item->role != 'Admin')
                                                            <a class="btn delete-record" data-id="{{ $item->id }}"
                                                                object="hubs" data-toggle="modal"
                                                                data-target="#delete-record-modal">
                                                                <i class="fa fa-trash" style="color: red;"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">
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
                                echo $hubs->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('hub.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('hub.script')
