@section('title', 'Role')
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
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Role</a></li>
                            <li class="breadcrumb-item active">Role List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Role List</h4>
                            <div class="mt-3 mb-3">

                                @include('layouts.alerts')

                                <div>
                                    <button type="button" id="btn-create"
                                        class="btn btn-sm btn-primary w-auto open-modal" modal-type="create">
                                        Create <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <table class="table table-borderless table-hover" id="role-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Permission</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($role))
                                        @foreach ($role as $item)
                                            <tr id="record-id-{{ $item->id }}">
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @php
                                                        $permissions = explode(', ', $item->permission);
                                                        foreach ($permissions as $value) {
                                                            echo '<span class="badge m-1 rounded-pill bg-primary">' . $value . '</span>';
                                                        }
                                                        
                                                    @endphp
                                                </td>
                                                <td>
                                                    @if ($item->name != 'Admin')
                                                        <a class="btn btn-edit open-modal" modal-type="update"
                                                            data-info="{{ json_encode($item) }} "><i
                                                                class="fa fa-edit"></i></a>
                                                        <a class="btn delete-record" data-id="{{ $item->id }}"
                                                            object="role" data-toggle="modal"
                                                            data-target="#delete-record-modal">
                                                            <i class="fa fa-trash" style="color: red;"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">
                                                <div class="alert alert-danger alert-dismissible fade show"
                                                    role="alert">
                                                    No data found.
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            <!-- End Tables without borders -->

                            @php
                                echo $role->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('role.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('role.script')
