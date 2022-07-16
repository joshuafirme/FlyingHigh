@section('title', 'Attributes')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Catalog</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Catalog</a></li>
                            <li class="breadcrumb-item active">Attributes</li>
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
                                        <th scope="col">Attribute</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($attributes))
                                        @foreach ($attributes as $item)
                                            <tr id="record-id-{{ $item->id }}">
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->type }}</td>
                                                <td>{{ Utils::formatDate($item->created_at) }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-primary btn-edit open-modal" data-toggle="tooltip"
                                                        data-placement="top" title="Edit"
                                                        data-info="{{ $item }}">
                                                        <i class="fa fa-edit"></i></a>
                                                    <a class="btn btn-sm btn-danger btn-delete" data-toggle="tooltip"
                                                        data-placement="top" title="Delete"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fa fa-trash"></i></a>
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
                                echo $attributes->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('attributes.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('attributes.script')
