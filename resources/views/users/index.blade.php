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
                            <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">User</a></li>
                            <li class="breadcrumb-item active">User List</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">User List</h4>
                            <table class="table" id="dataTableBuilder">
                                <thead>
                                    <tr>
                                        <th title="SL">SL</th>
                                        <th title="Avatar">Avatar</th>
                                        <th title="Name">Name</th>
                                        <th title="Email">Email</th>
                                        <th title="Phone">Phone</th>
                                        <th title="Role">Role</th>
                                        <th title="Status">Status</th>
                                        <th title="ACTION" width="55px" class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Invoice live url  -->
    <div class="modal fade" id="liveInvoiceUrl" tabindex="-1" role="dialog" aria-labelledby="liveInvoiceUrlTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Invoice Live URL</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="live-invoice-token" disabled>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary copy-url-btn">Copy URL</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
