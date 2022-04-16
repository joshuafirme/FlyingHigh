@section('title', 'Adjustment Remarks')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4>Catalog</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Catalog</a></li>
                            <li class="breadcrumb-item active">Reason for Return</li>
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
                                        Create <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <table class="table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($reasons))
                                        @foreach ($reasons as $item)
                                            <tr id="record-id-{{ $item->id }}">
                                                <td>{{ $item->reason }}</td>
                                                <td>@php
                                                    if ($item->status == 1) {
                                                        echo '<span class="badge rounded-pill bg-success">Active</span>';
                                                    } elseif ($item->status == 0) {
                                                        echo '<span class="badge rounded-pill bg-danger">Inactive</span>';
                                                    }
                                                @endphp</td>
                                                <td>{{ Utils::formatDate($item->created_at) }}</td>
                                                <td>
                                                    <a class="btn btn-edit open-modal" modal-type="update"
                                                        data-info="{{ json_encode($item) }} "><i
                                                            class="fa fa-edit"></i></a>

                                                    <a class="btn btn-delete" data-id="{{ $item->id }}"
                                                        object="return-reason">
                                                        <i class="fa fa-trash" style="color: red;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">
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
                                echo $reasons->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('return-reason.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('return-reason.script')
