<!-- sample modal content -->
<div id="modal-view-produk" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#449AFF;">
                    <h5 class="modal-title" id="exampleModalCenterTitle" style="color:white; font-weight: 500;">Detail Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nama Produk</label>
                            <p class="produk-name" style="font-weight: 500"></p>
                        </div>
                        <div class="col-md-12">
                            <label>Kode Produk</label>
                            <p class="produk-kode" style="font-weight: 500"></p>
                        </div>
                        <div class="col-md-12">
                            <label>Golongan Produk</label>
                            <p class="produk-golongan" style="font-weight: 500"></p>
                        </div>
                        <div class="col-md-12">
                            <label>Harga Produk</label>
                            <p class="produk-harga" style="font-weight: 500"></p>
                        </div>
                        <div class="col-md-12">
                            <label>Distributor</label>
                            <p class="distributor" style="font-weight: 500"></p>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cabang</th>
                                    <th>Jumlah Produk</th>
                                </tr>
                            </thead>
                            <tbody id="detail-stok-produk">
                                
                            </tbody>
                        </table>
                        
                        <div class="col-md-12">
                            <img class="produk-image" src="" style="width: 200px;"/>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
