<!-- sample modal content -->
<div id="modal-hapus-real-sales-permanen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="rounded m-auto">
                        <img src="{{ URL::asset('images/icon/alert-hapus.png') }}" alt="">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="m-auto" style="text-align:center;">
                        <p class="mb-0">Apakah anda yakin akan <b>menghapus</b></p>
                        <p ><b>permanen</b> data yang anda pilih?</p>
                    </div>
                </div>
                <div class="row">
                    <div action="" class="m-auto">@csrf
                        <button data-dismiss="modal" class="btn btn-link ">BATAL</button>
                        <input type="submit" class="btn btn-danger btn-delete-realsales-permanen-action" value="HAPUS">
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
