<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ url('/transfer-request/import') }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Import Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row g-3">
                
                <div class="col-md-12 mt-3">
                    <label class="form-label">Tracking #</label>
                    <input type="text" name="tracking_no" class="form-control" required>
                </div>
                <div class="col-12 mt-3">
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile" required>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="submit">Import</button>
            </div>
        </form>
    </div>
</div>

