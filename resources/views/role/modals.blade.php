<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <form action="#" method="post" class="modal-content">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Create Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <div class="modal-body row g-3 ">
            <div class="col-md-12">
              <label for="validationCustom01" class="form-label">Role name</label>
              <input type="text" class="form-control" name="name"  required>
            </div>

            <div class="row mt-4">
              <label for="">Permissions </label>
              <div class="col-md-4 my-2">
                <input type="checkbox" name="permission[]" id="chkbx-Dashboard" value="Dashboard"> Dashboard
              </div>
              <div class="col-md-4 my-2">
                <input type="checkbox" name="permission[]" id="chkbx-SystemUsers" value="System Users"> System Users
              </div>
              <div class="col-md-4 my-2">
                <input type="checkbox" name="permission[]" id="chkbx-ManageSite" value="Manage Site"> Manage Site
              </div>
              <div class="col-md-4 my-2">
                <input type="checkbox" name="permission[]" id="chkbx-Subscribers" value="Subscribers"> Subscribers
              </div>
              <div class="col-md-4 my-2">
                <input type="checkbox" name="permission[]" id="chkbx-ManagePages" value="Manage Pages"> Manage Pages
              </div>
            <div>
          

        </div>
      </div>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-sm btn-primary" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div><!-- End Large Modal-->