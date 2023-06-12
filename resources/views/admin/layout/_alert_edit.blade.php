<!-- sample modal content -->
<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="rounded m-auto">
                        <img src="{{ URL::asset('images/icon/alert-edit.png') }}" alt="">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="m-auto" style="text-align:center;">
                        <p class="mb-0">Apakah anda yakin ingin melakukan</p>
                        <p >perubahan?</p>
                    </div>
                </div>
                <div class="row">
                    <div class="m-auto">
                        <button data-dismiss="modal" class="btn btn-link ">BATAL</button>
                        <button type="button" class="edit-btn btn btn-primary waves-effect ">IYA</button>
                    </div>
                    {{-- <form action="" method="GET" class="m-auto">@csrf --}}
                        {{-- <input type="submit" value="submit"> --}}
                    {{-- </form> --}}
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
