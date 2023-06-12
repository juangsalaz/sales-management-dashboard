<!-- sample modal content -->
<div id="modal-hapus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
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
                        <p class="mb-0">Apakah anda yakin akan menghapus</p>
                        <p ><b class="item-name"></b> dari daftar <span class="page-name"></span></p>
                    </div>
                </div>
                <div class="row">
                    <form action="" method="POST" class="delete-form m-auto">@csrf
                        <button data-dismiss="modal" class="btn btn-link ">BATAL</button>
                        <button type="submit" class="btn btn-danger waves-effect">HAPUS</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
