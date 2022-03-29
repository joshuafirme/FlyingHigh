  @include('admin.includes.header')

  @include('admin.includes.top-nav')

  @include('admin.includes.side-nav')

  <main id="main" class="main">

      <div class="pagetitle">
          <h1>Manage Role</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Admin</li>
              <li class="breadcrumb-item">Manage Role</li>
            </ol>
          </nav>
        </div><!-- End Page Title -->        
          
            <section>
              <div class="card">
                  <div class="card-body">
                      <div class="mt-3 mb-3">
                        
                        @include('includes.alerts')

                        <div>
                          <button type="button" id="btn-create" class="btn btn-sm btn-primary w-auto open-modal" modal-type="create">
                              Add <i class="bi bi-plus"></i>
                          </button>
                        </div>
                      </div>
                      
                    <!-- Active Table -->
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
                          <tr id="record-id-{{$item->id}}">
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
                                <a class="btn btn-edit open-modal" modal-type="update" data-info="{{ json_encode($item)}} "><i class="bx bx-edit"></i></a>
                                <a class="btn delete-record" data-id="{{ $item->id }}" object="role" data-bs-toggle="modal" data-bs-target="#delete-record-modal">
                                  <i class="bx bx-trash" style="color: red;"></i>
                                </a>
                              @endif
                            </td>
                          </tr> 
                        @endforeach
                        @else
                        <tr>
                          <td colspan="6">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              No data found.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                          </td>
                        </tr>
                        @endif

                      </tbody>
                    </table>
                    <!-- End Tables without borders -->

                    @php
                     echo $role->links("pagination::bootstrap-4");
                    @endphp
                  </div>
                </div>
      
            </section>

  </main><!-- End #main -->

  @include('admin.role.modals')

  @include('admin.includes._vendor_scripts')

  @include('admin.includes._global_scripts')

  @include('admin.role.script')

  @include('admin.includes.footer')

