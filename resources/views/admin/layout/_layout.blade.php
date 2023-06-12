<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('images/favicon.png')}}">
    <title>Sales Management</title>
    <!-- This page CSS -->
    <link href="{{URL::asset('node_modules/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{URL::asset('node_modules/bootstrap-datepicker/bootstrap-datepicker3.min.css')}}" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="{{ URL::asset('node_modules/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ URL::asset('css/style.min.css')}}" rel="stylesheet">
    {{-- glyphicon --}}
    <link href="{{ URL::asset('css/glyphicon.css')}}" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="{{ URL::asset('css/pages/dashboard1.css')}}" rel="stylesheet">
    {{-- select2 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <!-- Morris CSS -->
    <link href="{{ URL::asset('node_modules/morrisjs/morris.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style>
        @keyframes slide {
            from { left: 100%;}
            to { left: -100%;}
        }

        @-webkit-keyframes slide {
            from { left: 100%;}
            to { left: -100%;}
        }

        #wrap-text {
            height:100px;
            line-height:50px;
            overflow:hidden;
            position:relative;
            background: #00D770;
            margin-top: 40px;
        }

        #wrap-text p {
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:120px;
            font-size:24px;
            margin-top: 20px;
            color: white;
            animation-name: slide;
            animation-duration: 22s;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
            -webkit-animation-name: slide;
            -webkit-animation-duration: 22s;
            -webkit-animation-timing-function:linear;
            -webkit-animation-iteration-count: infinite;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">HDPI</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->

        @include('admin.layout._header')
        @include('admin.layout._left_sidebar')

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            @if(isset($running_teks->teks))
                @if($running_teks->teks != '')
                    <div class="row" id="wrap-text">
                        <?php echo $running_teks->teks;?>
                    </div>
                @endif
            @endif
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid mt-4 pt-4">
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session()->get('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session()->has('failed'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session()->get('failed') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                @yield('content')
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            © 2019
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ URL::asset('node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="{{ URL::asset('node_modules/popper/popper.min.js')}}"></script>
    <script src="{{ URL::asset('node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

    <script src="{{ URL::asset('node_modules/moment/moment.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ URL::asset('js/perfect-scrollbar.jquery.min.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{ URL::asset('js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{ URL::asset('js/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{ URL::asset('js/custom.min.js')}}"></script>
    <!-- ============================================================== -->

    <script src="{{ URL::asset('node_modules/Chart.js/Chart.min.js')}}"></script>
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="{{ URL::asset('node_modules/raphael/raphael-min.js')}}"></script>
    <script src="{{ URL::asset('node_modules/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- Popup message jquery -->
    <script src="{{ URL::asset('node_modules/toast-master/js/jquery.toast.js')}}"></script>
    <!-- Chart JS -->
    <script src="{{ URL::asset('node_modules/toast-master/js/jquery.toast.js')}}"></script>
    <!-- jQuery peity -->
    <script src="{{ URL::asset('node_modules/peity/jquery.peity.min.js')}}"></script>
    <script src="{{ URL::asset('node_modules/peity/jquery.peity.init.js')}}"></script>
    <!--- Chart Js -->

    <!-- This is data table -->
    <script src="{{ URL::asset('node_modules/datatables/datatables.min.js')}}"></script>
    <script src="{{ URL::asset('js/autoNumeric.js')}}"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    {{-- select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

    <!-- end - This is for export functionality only -->
    <script>
    $(function() {
        $('#myTable').DataTable({
                "columnDefs": [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
            });

        $('.myTable').DataTable();
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        $('#datepickerrange').datepicker({
            format: "yyyy-mm-dd",
        });

        $('.datepicker').datepicker({
            format: "MM",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        });

        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });

        $('.tanggal-pengingat').datepicker({
            format: "dd-mm-yyyy",
            orientation: "bottom auto",
            autoclose: true
        });

        $('.yearpicker').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true
        });

        function perhitungan() {
            // harga = formatInteger($("#harga-produk").val());
            harga = $("#harga-produk").autoNumeric('get');
            kuantiti = $("#kuantiti-input").autoNumeric('get');

            // $("#value-gross").val(formatNumber(harga*kuantiti));
            $("#value-gross").autoNumeric('set',harga*kuantiti)

            diskon = $("#diskon-input").autoNumeric('get');
            // value_gross = formatInteger($("#value-gross").val());
            value_gross = $("#value-gross").autoNumeric('get');

            // $("#diskon-value").val(formatNumber(value_gross*diskon/100));
            $("#diskon-value").autoNumeric('set',value_gross*diskon/100);

            // diskon_value = formatInteger($("#diskon-value").val());
            diskon_value = $("#diskon-value").autoNumeric('get');
            // $("#value-nett").val(formatNumber(value_gross-diskon_value));
            $("#value-nett").autoNumeric('set',value_gross-diskon_value);
        }

        $("#select-product").change(function() {
            harga = $('option:selected', '#select-product').attr('harga');
            // $("#harga-produk").val(formatNumber(harga));
            $("#harga-produk").autoNumeric('set',harga);

            perhitungan()
        });

        $("#kuantiti-input").change(function(){
            // harga = formatInteger($("#harga-produk").val());
            harga = $("#harga-produk").autoNumeric('get');
            kuantiti = $("#kuantiti-input").autoNumeric('get');

            // $("#value-gross").val(formatNumber(harga*kuantiti));
            $("#value-gross").autoNumeric('set',harga*kuantiti);

            perhitungan()
        }).keyup(function(){
            // harga = formatInteger($("#harga-produk").val());
            // kuantiti = $("#kuantiti-input").autoNumeric('get');
            harga = $("#harga-produk").autoNumeric('get');
            kuantiti = $("#kuantiti-input").autoNumeric('get');

            // $("#value-gross").val(formatNumber(harga*kuantiti));
            $("#value-gross").autoNumeric('set',harga*kuantiti);

            perhitungan()
        });

        $("#diskon-input").keyup(function(){
            if ($("#diskon-input").val() < 0) $("#diskon-input").autoNumeric('set',0);
            if ($("#diskon-input").val() > 100) $("#diskon-input").autoNumeric('set',100)

            diskon = $("#diskon-input").autoNumeric('get');
            // value_gross = formatInteger($("#value-gross").val());
            value_gross = $("#value-gross").autoNumeric('get');

            // $("#diskon-value").val(formatNumber(value_gross*diskon/100));
            $("#diskon-value").autoNumeric('set',value_gross*diskon/100)

            // diskon_value = formatInteger($("#diskon-value").val());
            diskon_value = $("#diskon-value").autoNumeric('get');
            // $("#value-nett").val(formatNumber(value_gross-diskon_value));
            $("#value-nett").autoNumeric('set',value_gross-diskon_value);
        }).change(function(){
            if ($("#diskon-input").val() < 0) $("#diskon-input").autoNumeric('set',0);
            if ($("#diskon-input").val() > 100) $("#diskon-input").autoNumeric('set',100)

            diskon = $("#diskon-input").autoNumeric('get');
            // value_gross = formatInteger($("#value-gross").val());
            value_gross = $("#value-gross").autoNumeric('get');

            // $("#diskon-value").val(formatNumber(value_gross*diskon/100));
            $("#diskon-value").autoNumeric('set',value_gross*diskon/100)

            // diskon_value = formatInteger($("#diskon-value").val());
            diskon_value = $("#diskon-value").autoNumeric('get');
            // $("#value-nett").val(formatNumber(value_gross-diskon_value));
            $("#value-nett").autoNumeric('set',value_gross-diskon_value);
        });

    });

    </script>

    <script>
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
        }
        function formatInteger(num) {
            return num.replace(/\./g, '')
        }
        $(".view_btn").click(function(){
            id_produk = $(this).attr('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-produk/produk-details/'+id_produk,
                dataType: 'json',
                success: function(data) {

                    console.log(data);

                    $('.produk-name').text(data[0].nama);
                    $('.produk-kode').text(data[0].kode);
                    $('.produk-golongan').text(data[0].golongan);
                    $('.produk-harga').text(formatNumber(data[0].harga));
                    $('.distributor').text(data[0].nama_distributor);

                    src = data[0].gambar;

                    $('.produk-image').attr('src', '/images/produks/no-image-found.jpg');
                    if (src != "") {
                        $('.produk-image').attr('src', '/images/produks/'+src);
                    }

                    $('.item-name').text(data[0].nama);
                    $("#detail-stok-produk").empty();
                    for (let i = 0; i < data[0].details_stok.length; i++) {
                        $("#detail-stok-produk").append(
                                '<tr>'+
                                    '<td>'+data[0].details_stok[i]['nama_cabang']+'</td>'+
                                    '<td>'+data[0].details_stok[i]['jumlah']+'</td>'+
                                '</tr>'
                            );
                    }

                    $('#modal-view-produk').modal();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $(".view_estimasi_btn").click(function(){
            id_estimasi = $(this).attr('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-marketing/estimasi/estimasi-details/'+id_estimasi,
                dataType: 'json',
                success: function(data) {
                    console.log(data);

                    data_diskon = data[0].diskon;
                    data_diskon = data_diskon.toString();
                    data_diskon = data_diskon.replace(".", ",");

                    $("#estimasi-bulan").text(data[0].bulan);
                    $("#estimasi-distributor").text(data[0].nama_distributor);
                    $("#estimasi-value-gross").text(formatNumber(data[0].value_gross));
                    $("#estimasi-tahun").text(data[0].tahun);
                    $("#estimasi-cabang").text(data[0].nama_cabang);
                    $("#estimasi-diskon").text(data_diskon);
                    $("#estimasi-produk").text(data[0].nama_produk);
                    $("#estimasi-harga-produk").text(formatNumber(data[0].harga_produk));
                    $("#estimasi-diskon-value").text(formatNumber(data[0].diskon_value));
                    $("#estimasi-user").text(data[0].nama_user);
                    $("#estimasi-outlet").text(data[0].nama_outlet);
                    $("#estimasi-value-nett").text(formatNumber(data[0].value_nett));
                    $("#estimasi-coverage-area").text(data[0].nama_coverage_area);
                    $("#estimasi-kuantiti").text(data[0].kuantiti);
                    $("#estimasi-status").text(data[0].status);

                    $('#modal-view-estimasi').modal();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $(".view_real_sales_btn").click(function(){
            id_real_sales = $(this).attr('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-marketing/real-sales/real-sales-details/'+id_real_sales,
                dataType: 'json',
                success: function(data) {
                    data_diskon = data[0].diskon;
                    data_diskon = data_diskon.toString();
                    data_diskon = data_diskon.replace(".", ",");

                    $("#real-sales-bulan").text(data[0].bulan);
                    $("#real-sales-distributor").text(data[0].nama_distributor);
                    $("#real-sales-value-gross").text(formatNumber(data[0].value_gross));
                    $("#real-sales-tahun").text(data[0].tahun);
                    $("#real-sales-cabang").text(data[0].nama_cabang);
                    $("#real-sales-diskon").text(data_diskon);
                    $("#real-sales-produk").text(data[0].nama_produk);
                    $("#real-sales-harga-produk").text(formatNumber(data[0].harga_produk));
                    $("#real-sales-diskon-value").text(formatNumber(data[0].diskon_value));
                    $("#real-sales-user").text(data[0].nama_user);
                    $("#real-sales-outlet").text(data[0].nama_outlet);
                    $("#real-sales-value-nett").text(formatNumber(data[0].value_nett));
                    $("#real-sales-coverage-area").text(data[0].nama_coverage_area);
                    $("#real-sales-kuantiti").text(data[0].kuantiti);
                    $("#real-sales-status").text(data[0].status);

                    $('#modal-view-realsales').modal();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $(".view_target_dist_btn").click(function(){
            id_target_dist = $(this).attr('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-marketing/target-distributor/details/'+id_target_dist,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $("#target-dist-bulan").text(data[0].bulan);
                    $("#target-dist-area").text(data[0].coverage_area);
                    $("#target-dist-tahun").text(data[0].tahun);
                    $("#target-dist-cabang").text(data[0].cabang);
                    $("#target-dist-dist").text(data[0].nama_distributor);
                    $("#target-dist-produk").text(data[0].produk);
                    $("#target-dist-user").text(data[0].nama_user);
                    $("#target-dist-target").text(formatNumber(data[0].target));

                    $('#modal-view-target-dist').modal();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
        cabang_area();

        function cabang_area() {
            $(".select-coverage-area").change(function(){
                id = $(this).attr('id');

                id_coverage_area =  $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '/admin/manajemen-produk/get-cabang-by-area/'+id_coverage_area,
                    dataType: 'json',
                    success: function(data) {
                        $(".list-cabang-option-"+id).remove();
                        for (let i = 0; i < data.length; i++) {
                            $(".select-cabang-"+id).append('<option class="list-cabang-option-'+id+'" value="'+data[i].id+'">'+data[i].nama_cabang+'</option>');
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        }

        $("#add-stok-cabang").click(function(){
            count_row = $(".select-coverage-area").length;
            next_row = count_row+1;

            $("#append-stok").append(
                '<div class="form-row" id="block-stok-form-'+next_row+'">'+
                    '<div class="form-group col-md-4">'+
                        '<select class="form-control select-coverage-area" id="'+next_row+'" name="coverage_areas[]">'+
                            '<option value="0">Pilih Coverage Area</oprion>'+
                                '@if (isset($coverage_areas))'+
                                    '@foreach ($coverage_areas as $area)'+
                                        '<option value="{{ $area->id }}">{{ $area->nama_coverage_area }}</oprion>'+
                                    '@endforeach'+
                                '@endif'+
                        '</select>'+
                    '</div>'+
                    '<div class="form-group col-md-4">'+
                        '<select class="form-control select-cabang-'+next_row+'" name="cabangs[]">'+
                            '<option value="0">Pilih Cabang</oprion>'+

                        '</select>'+
                    '</div>'+
                    '<div class="form-group col-md-2">'+
                        '<input type="text" class="form-control" id="jumlah" name="jumlah[]" placeholder="Jumlah Produk">'+
                    '</div>'+
                    '<div class="form-group col-md-2" id="delete-button-form-'+next_row+'">'+
                    '</div>'+
                '</div>'
            );

            $("#delete-button-form-"+count_row).empty();
            $("#delete-button-form-"+next_row).append('<button type="button" id="'+next_row+'" class="btn btn-danger remove-stok-cabang" onclick="remove_stock_cabang_form('+next_row+')">-</button>');

            cabang_area();
        });

        function remove_stock_cabang_form(id) {
            $("#block-stok-form-"+id).remove();

            prev_row = id-1;
            $("#delete-button-form-"+prev_row).append('<button type="button" id="'+prev_row+'" class="btn btn-danger remove-stok-cabang" onclick="remove_stock_cabang_form('+prev_row+')">-</button>');
        }

        $(".delete_btn").click(function(){
            $('#modal-hapus').modal();
            $('.item-name').text($(this).attr('nama'));
            $('.page-name').text($(this).attr('page'));
            $('.delete-form').attr('action',$(this).attr('slug')+"/"+$(this).attr('id'));
        });

        $(".btn-delete-permanen").click(function(){
            $("#trash-action").val("hapus");
            $('#modal-hapus-permanen').modal();
        });

        $(".btn-delete-real-sales-permanen").click(function(){
            $("#trash-real-sales-action").val("hapus");
            $('#modal-hapus-real-sales-permanen').modal();
        });

        $(".btn-delete-produks-permanen").click(function(){
            $("#trash-produks-action").val("hapus");
            $('#modal-hapus-produks-permanen').modal();
        });

        $('.btn-delete-permanen-action').click(function(){
            $('.delete-permanen-form').submit();
            event.preventDefault();
        });

        $('.btn-delete-realsales-permanen-action').click(function(){
            $('.delete-permanen-real-sales-form').submit();
            event.preventDefault();
        });

        $('.btn-delete-produks-permanen-action').click(function(){
            $('.delete-permanen-produks-form').submit();
            event.preventDefault();
        });

        $(".submit-edit").click( function(event){
            $('#modal-edit').modal();
        });
        $('.edit-btn').click(function(){
            $('.edit-form').submit();
            event.preventDefault();
        });
    </script>
    <script>
        @for ($i_menu = 1; $i_menu < 12; $i_menu++)
            $(".menu-{{$i_menu}}").click(function(){
                @for ($i_sub_menu1 = 1; $i_sub_menu1 < 6; $i_sub_menu1++)
                    if ($(".menu-{{$i_menu}}-{{$i_sub_menu1}}").prop("disabled")) {
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}").prop("checked",true);
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}").prop("disabled",false);
                        @for ($i_sub_menu2 = 1; $i_sub_menu2 < 6; $i_sub_menu2++)
                            $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("checked",true)
                            $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("disabled",false);
                        @endfor
                    } else {
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}").prop("checked",false);
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}").prop("disabled",true);
                        @for ($i_sub_menu2 = 1; $i_sub_menu2 < 6; $i_sub_menu2++)
                            $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("checked",false);
                            $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("disabled",true);
                        @endfor
                    }
                @endfor
            });
            @for ($i_sub_menu1 = 1; $i_sub_menu1 < 6; $i_sub_menu1++)
            $(".menu-{{$i_menu}}-{{$i_sub_menu1}}").click(function(){
                var subChecked = $('input.sub-{{$i_menu}}:checkbox:checked').length;
                if (subChecked<1) {
                    $(".menu-{{$i_menu}}").prop("checked",false);
                    $(".sub-{{$i_menu}}").prop("disabled",true);
                }
                @for ($i_sub_menu2 = 1; $i_sub_menu2 < 6; $i_sub_menu2++)

                    if ($(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("disabled")) {
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("checked",true);
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("disabled",false);
                    } else {
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("checked",false);
                        $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").prop("disabled",true);
                    }
                @endfor
            });
                @for ($i_sub_menu2 = 1; $i_sub_menu2 < 6; $i_sub_menu2++)
                $(".menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}").click(function(){
                    var sub2Checked = $('input.sub2-{{$i_menu}}-{{$i_sub_menu1}}:checkbox:checked').length;
                    if (sub2Checked<1) {
                        var subChecked = $('input.sub-{{$i_menu}}:checkbox:checked').length;
                        if (subChecked<1) {
                            $(".menu-{{$i_menu}}").prop("checked",false);
                            $(".sub-{{$i_menu}}").prop("disabled",true);
                        }
                    }
                });
                @endfor
            @endfor
        @endfor
    </script>
    {{-- Script Data Distributor --}}
    <script>
        $('#select-checkboxes, #checkboxes').mouseover(function (event){
            showCheckboxes();
        }).mouseout(function (event){
            showCheckboxes();
        });
        $('#select-checkboxesdist, #checkboxesdist').mouseover(function (event){
            showCheckboxesDist();
        }).mouseout(function (event){
            showCheckboxesDist();
        });
        $('#select-checkboxescabang, #checkboxescabang').mouseover(function (event){
            showCheckboxesCabang();
        }).mouseout(function (event){
            showCheckboxesCabang();
        });
        $('#select-checkboxesproduk, #checkboxesproduk').mouseover(function (event){
            showCheckboxesProduk();
        }).mouseout(function (event){
            showCheckboxesProduk();
        });
        $('#semua-area').click(function () {
            if($('#semua-area').prop("checked")){
                $('.area-checkbox').prop("checked",true);
            }
            else {
                $('.area-checkbox').prop("checked",false);
            }
        })
        $('#semua-dist').click(function () {
            if($('#semua-dist').prop("checked")){
                $('.dist-checkbox').prop("checked",true);
            }
            else {
                $('.dist-checkbox').prop("checked",false);
            }
        })
        $('#semua-cabang').click(function () {
            if($('#semua-cabang').prop("checked")){
                $('.cabang-checkbox').prop("checked",true);
            }
            else {
                $('.cabang-checkbox').prop("checked",false);
            }
        })
        $('#semua-produk').click(function () {
            if($('#semua-produk').prop("checked")){
                $('.produk-checkbox').prop("checked",true);
            }
            else {
                $('.produk-checkbox').prop("checked",false);
            }
        })
    </script>

    <script>
    $(document).ready(function(){
        $("#check-notif-all").click(function(){
            if($(this).prop("checked") == true){
                $(".notif-check").prop("checked", true);
            }
            else if($(this).prop("checked") == false){
                $(".notif-check").prop("checked", false);
            }
        });

        $('.input-no-dec').autoNumeric('init',{
            aSep: '.',
            aDec: ',',
            aPad: false,
            wEmpty: 'zero',
            lZero: 'deny',
            mDec: '0'
        });
        $('.input-dec').autoNumeric('init',{
            aSep: '.',
            aDec: ',',
            aPad: false,
            wEmpty: 'zero',
            lZero: 'deny',
            mDec: '2'
        });
        $('.input-diskon').autoNumeric('init',{
            aSep: '.',
            aDec: ',',
            aPad: false,
            wEmpty: 'zero',
            lZero: 'deny',
            vMax: '100'
        });
        $("#harga-input").keyup(function(event){
            if(event.which >= 37 && event.which <= 40) return;
            $(this).val(function(index, value) {
                return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                ;
            });
        });
        $('.js-example-basic-single').select2({
            height: '38px'
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/admin/dashboard/real-sales-data',
            dataType: 'json',
            data: {},
            success: function(data) {
                new Chart(document.getElementById("real-sales-chart"),
                {
                    "type":"line",
                    "data":{"labels":data.bulan,
                    "datasets":[{
                                    "label":"Jumlah Real Sales",
                                    "data":data.jumlah,
                                    "fill":false,
                                    "borderColor":"rgb(75, 192, 192)",
                                    "lineTension":0.1
                                }]
                },"options":{}});
            },
            error: function(data) {
                console.log(data);
            }
        });

        $("#filter-dist").change(function(){
            distributor_id = $("#filter-dist").val();

            $.ajax({
                type: 'POST',
                url: '/admin/dashboard/stok-produk-dist/'+distributor_id,
                dataType: 'json',
                success: function(data) {

                    var isi = [];
                    var warna = [];

                    function getRandomColor() {
                    var letters = '0123456789ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++) {
                        color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                    }

                    // for (var i in data.produk) {
                    //     isi.push({label:data.produk[i],value:data.jumlah[i]});
                    //     warna.push(getRandomColor());
                    // }

                    // $('#stok-produk').html("");
                    // new Morris.Donut({
                    //     element: 'stok-produk',
                    //     data: isi,
                    //     resize: true,
                    //     colors:warna
                    // });

                    var total_value=0;
                    for (var i in data.produk) {
                        if(data.jumlah[i]>0){
                            total_value++;
                        }
                        isi.push({label:data.produk[i],value:data.jumlah[i]});
                        warna.push(getRandomColor());
                    }
                    if(total_value > 0){
                        $('#stok-produk').html("");
                        new Morris.Donut({
                            element: 'stok-produk',
                            data: isi,
                            resize: true,
                            colors:warna
                        });
                    } else {
                        $('#stok-produk').html("<h3 style='margin-top:20px;text-align:center'>Semua Produk Habis</h3>");
                    }
                    // var dynamicColors = function() {
                    //     var r = Math.floor(Math.random() * 255);
                    //     var g = Math.floor(Math.random() * 255);
                    //     var b = Math.floor(Math.random() * 255);
                    //     return "rgb(" + r + "," + g + "," + b + ")";
                    // };

                    // console.log(getRandomColor());
                    // var ict_unit = [];
                    // var efficiency = [];
                    // var coloR = [];


                    // for (var i in data.produk) {
                    //     ict_unit.push("ICT Unit " + data.produk[i].ict_unit);
                    //     efficiency.push(data.produk[i].efficiency);
                    //     coloR.push(dynamicColors());
                    // }

                    // new Chart(document.getElementById("chart3"),
                    // {
                    //     "type":"pie",
                    //     "data":{"labels":data.produk,
                    //     "datasets":[{
                    //         "label":"Data Stok Produk",
                    //         "data":data.jumlah,
                    //         "backgroundColor":coloR}
                    //     ]}
                    // });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $.ajax({
            type: 'POST',
            url: '/admin/dashboard/stok-produk',
            dataType: 'json',
            data: {},
            success: function(data) {

                var isi = [];
                var warna = [];

                function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
                }
                var total_value=0;
                for (var i in data.produk) {
                    if(data.jumlah[i]>0){
                        total_value++;
                    }
                    isi.push({label:data.produk[i],value:data.jumlah[i]});
                    warna.push(getRandomColor());
                }
                if(total_value > 0){
                    new Morris.Donut({
                        element: 'stok-produk',
                        data: isi,
                        resize: true,
                        colors:warna
                    });
                } else {
                    $('#stok-produk').html("<h3 style='margin-top:20px;text-align:center'>Semua Produk Habis</h3>");
                }
                // var ict_unit = [];
                // var efficiency = [];
                // var coloR = [];

                // var dynamicColors = function() {
                //     var r = Math.floor(Math.random() * 255);
                //     var g = Math.floor(Math.random() * 255);
                //     var b = Math.floor(Math.random() * 255);
                //     return "rgb(" + r + "," + g + "," + b + ")";
                // };

                // for (var i in data.produk) {
                //     ict_unit.push("ICT Unit " + data.produk[i].ict_unit);
                //     efficiency.push(data.produk[i].efficiency);
                //     coloR.push(dynamicColors());
                // }

                // new Chart(document.getElementById("chart3"),
                // {
                //     "type":"pie",
                //     "data":{"labels":data.produk,
                //     "datasets":[{
                //         "label":"Data Stok Produk",
                //         "data":data.jumlah,
                //         "backgroundColor":coloR}
                //     ]}
                // });
            },
            error: function(data) {
                console.log(data);
            }
        });

        $.ajax({
            type: 'POST',
            url: '/admin/dashboard/real_sales_by_estimsi',
            dataType: 'json',
            data: {},
            success: function(data) {
                new Chart(document.getElementById("sales_by_estimate_dashboard"),
                {
                    "type":"doughnut",
                    "data":{"labels":["Realization","Target"],
                    "datasets":[{
                        "label":"Real Sales By Estimasi",
                        "data":data.nilai,
                        "backgroundColor":["rgb(54, 162, 235)","rgb(201, 233, 255)"]}
                    ]}
                });

                $("#user-achivement").text(data.user);
                $("#real_sales_by_estimasi_prosentase_dashboard").text(data.nilai[0]+"%");

            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $(document).ready(function(){

        $("#estimasi-dm").change(function() {
            id_user = $("#estimasi-dm").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-marketing/estimasi/get-coverage-area/'+id_user,
                dataType: 'json',
                success: function(data) {
                    $(".list-estimasi2").remove();
                    for (let i = 0; i < data.length; i++) {
                        $("#estimasi-coverage-area2").append('<option class="list-estimasi2" value="'+data[i].id+'">'+data[i].nama_coverage_area+'</option>');
                    }

                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $("#estimasi-coverage-area2").change(function() {
            coverage_area_id = $("#estimasi-coverage-area2").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-marketing/estimasi/get-cabang-area/'+coverage_area_id,
                dataType: 'json',
                success: function(data) {
                   // console.log(data);
                    $(".list-cabang").remove();
                    for (let i = 0; i < data.length; i++) {
                        $("#estimasi-cabang2").append('<option class="list-cabang" value="'+data[i].id+'">'+data[i].nama_cabang+'</option>');
                    }

                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $("#estimasi-cabang2").change(function() {
            cabang_id = $("#estimasi-cabang2").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-marketing/estimasi/get-distributor/'+cabang_id,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $(".list-distributor").remove();
                    for (let i = 0; i < data.length; i++) {
                        $("#estimasi-distributor2").append('<option class="list-distributor" value="'+data[i].id+'">'+data[i].nama_distributor+'</option>');
                    }

                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $("#estimasi-distributor2").change(function() {
            distributor_id = $("#estimasi-distributor2").val();
            cabang_id = $("#estimasi-cabang2").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-marketing/estimasi/get-produks/'+distributor_id+'/'+cabang_id,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $(".list-produk").remove();
                    for (let i = 0; i < data.length; i++) {
                        $("#select-product").append('<option value="'+data[i].id+'" harga="'+data[i].harga+'" class="list-produk">'+data[i].nama_produk+'</option>');
                    }

                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        if ($(".jabatan option:selected").text() !== 'Direktur') {
            $(".coverage_area_options").css("display", "block");
            $(".area-checkbox").prop('disabled', false);
            $(".is_checkbox").attr('enabled', 'enabled');
            $(".is_checkbox").removeAttr('disabled');
        }

        $(".jabatan").change(function(){
            if ($(".jabatan option:selected").text() !== 'Direktur') {
                $(".coverage_area_options").css("display", "block");
                $(".area-checkbox").prop('disabled', false);
                $(".is_checkbox").attr('enabled', 'enabled');
                $(".is_checkbox").removeAttr('disabled');
            } else {
                $(".coverage_area_options").css("display", "none");
                $(".area-checkbox").prop('disabled', true);
                $(".is_checkbox").attr('disabled', 'disabled');
                $(".is_checkbox").removeAttr('enabled');
            }
        });

        $("#filter-submit").click(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            user_id = $("#user").val();
            start = $("#start").val();
            end = $("#end").val();
            distributor = $("#distributor").val();

            $.ajax({
                type: 'POST',
                url: '/admin/laporan/datas',
                dataType: 'json',
                data: {
                    "user_id":user_id,
                    "start":start,
                    "end":end,
                    "distributor":distributor
                },
                success: function(data) {
                    console.log(data);
                    new Chart(document.getElementById("realsales_by_estimasi_laporan"),
                    {
                        "type":"doughnut",
                        "data":{"labels":["Realization","Target"],
                        "datasets":[{
                            "label":"Real Sales By Estimasi",
                            "data":data.realsales_by_estimasi.nilai,
                            "backgroundColor":["rgb(54, 162, 235)","rgb(201, 233, 255)"]}
                        ]}
                    });

                    $("#realsales_by_estimasi_laporan_prosentase").text(data.realsales_by_estimasi.nilai[0]+"%");

                    new Chart(document.getElementById("realsales_by_target"),
                    {
                        "type":"doughnut",
                        "data":{"labels":["Realization","Target"],
                        "datasets":[{
                            "label":"Real Sales By Estimasi",
                            "data":data.realsales_by_target.nilai,
                            "backgroundColor":["rgb(54, 162, 235)","rgb(201, 233, 255)"]}
                        ]}
                    });

                    $("#realsales_by_target_laporan_prosentase").text(data.realsales_by_target.nilai[0]+"%");

                    new Chart(document.getElementById("diskon_rata_by_dist"),
                    {
                        "type":"doughnut",
                        "data":{"labels":["Realization","Target"],
                        "datasets":[{
                            "label":"Real Sales By Estimasi",
                            "data":data.diskon_rata_by_dist.nilai,
                            "backgroundColor":["rgb(54, 162, 235)","rgb(201, 233, 255)"]}
                        ]}
                    });

                    $("#diskon_rata_by_dist_laporan_prosentase").text(data.diskon_rata_by_dist.nilai[0]+"%");

                    new Chart(document.getElementById("realsales_diskon_value_by_estimasi_diskon_value"),
                    {
                        "type":"doughnut",
                        "data":{"labels":["Realization","Target"],
                        "datasets":[{
                            "label":"Real Sales By Estimasi",
                            "data":data.realsales_diskon_value_by_estimasi_diskon_value.nilai,
                            "backgroundColor":["rgb(54, 162, 235)","rgb(201, 233, 255)"]}
                        ]}
                    });

                    $("#realsales_diskon_value_by_estimasi_diskon_value_prosentase").text(data.realsales_diskon_value_by_estimasi_diskon_value.nilai[0]+"%");

                    new Chart(document.getElementById("realsales_value_nett_by_estimasi_value_nett"),
                    {
                        "type":"doughnut",
                        "data":{"labels":["Realization","Target"],
                        "datasets":[{
                            "label":"Real Sales By Estimasi",
                            "data":data.realsales_value_nett_by_estimasi_value_nett.nilai,
                            "backgroundColor":["rgb(54, 162, 235)","rgb(201, 233, 255)"]}
                        ]}
                    });

                    $("#realsales_value_nett_by_estimasi_value_nett_prosentase").text(data.realsales_value_nett_by_estimasi_value_nett.nilai[0]+"%");
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    });
</script>
{{-- morris-chart --}}
<script src="{{ URL::asset('node_modules/raphael/raphael-min.js') }}"></script>
<script src="{{URL::asset('node_modules/morrisjs/morris.js')}}"></script>
</body>

</html>
