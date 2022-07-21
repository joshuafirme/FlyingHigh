@section('title', 'Users')
@include('layouts.header')

@include('layouts.top-nav')

@include('layouts.side-nav')

<div class="content-page">
    <div class="content">
        <div class="container-fluid" id="app">
            <div class="page-title-box">
                <h4 class="page-title">Administration</h4>
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">Administration</a></li>
                            <li class="breadcrumb-item active">User</li>
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

                                <div class="float-right">
                                    <form action="" method="get">
                                        <div class="input-group mb-3">
                                            <input type="text" name="key" class="form-control" placeholder="Search"
                                                aria-label="Search" required>
                                            <button class="btn btn-outline-secondary" type="submit"><i
                                                    class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <table class="table table-borderless table-hover" id="users-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Assigned Branch</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($users))
                                        @foreach ($users as $item)
                                            <tr id="record-id-{{ $item->id }}">
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td><span
                                                        class="badge rounded-pill bg-primary">{{ $item->role }}</span>
                                                </td>
                                                <td>{{ $item->branch_name }}</td>
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
                                                    @if ($item->role != 'Admin')
                                                        <a class="btn delete-record" data-id="{{ $item->id }}"
                                                            object="users" data-toggle="modal"
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
                            @php
                                echo $users->links('pagination::bootstrap-4');
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('users.modals')

@include('layouts.footer')

@include('scripts._global_scripts')

@include('users.script')
