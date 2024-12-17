<div class="modal fade" tabindex="-1" role="dialog" id="modal_tambah_kode_rak">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Rak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-group">
                        <label>Kode Rak</label>
                        <input type="text" class="form-control" name="kd_rak" id="kd_rak">
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-kd_rak"></div>
                        <div class="form-group">
                            <label>Nama Rak</label>
                            <input type="text" class="form-control" name="nm_rak" id="nm_rak">
                            <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nm_rak"></div>
                        </div>

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-primary" id="store">Tambah</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
