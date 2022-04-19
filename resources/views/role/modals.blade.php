<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="#" method="post" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3 ">
                <div class="col-md-12">
                    <label for="validationCustom01" class="form-label">Role name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="col-md-12 mt-4">
                    <label for="validationCustom01" class="form-label">Permissions</label>
                </div>

                <div class="col-md-12">
                    <div class="row my-2">
                        <div class="col-8 pt-1">
                            <div class="custom-control pl-0">
                                <label for="customCheck-all">All Permission</label>
                            </div>
                        </div>
                        <div class="col-4 pt-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" id="customCheck-all" value="all">
                            </div>
                        </div>
                    </div>

                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1"><strong>Dashboard
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permission[]" value="Dashboard"
                                        class="ic-parent-permission" id="chkbx-Dashboard" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1"><strong>Product
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permission[]" value="Product"
                                        class="ic-parent-permission" id="chkbx-Product" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label><strong>Administration
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="ic-parent-permission" value="administration"
                                        id="chkbx-all-administration">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>User</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permission[]" id="chkbx-User" value="User"
                                        class="parent-identy-administration">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Role</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permission[]" id="chkbx-Role" value="Role"
                                        class="parent-identy-administration">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label><strong>Pickup
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="ic-parent-permission" value="pickup"
                                        id="chkbx-all-pickup">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>For Pickup</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permission[]" id="chkbx-ForPickup" value="For Pickup"
                                        class="parent-identy-pickup">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Picked Up</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permission[]" id="chkbx-PickedUp" value="Picked Up"
                                        class="parent-identy-pickup">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Returned</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permission[]" id="chkbx-Returned" value="Returned"
                                        class="parent-identy-pickup">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label><strong>Hub
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="ic-parent-permission" value="hub" id="chkbx-all-hub">
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($hubs as $item)
                        <div>
                            <div class="row">
                                <div class="col-8 pt-1">
                                    <div class="custom-control">
                                        <label>{{ $item->name }}</label>
                                    </div>
                                </div>
                                <div class="col-4 pt-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="permission[]" class="parent-identy-hub"
                                            id="chkbx-{{ preg_replace('/\s+/', '', $item->name) }}"
                                            value="{{ $item->name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
