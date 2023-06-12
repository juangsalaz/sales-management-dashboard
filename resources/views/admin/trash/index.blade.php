@extends('admin.layout._layout')

@section('title', 'Trash')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_hapus_permanen')
@include('admin.layout._alert_hapus_realsales_permanen')
@include('admin.layout._alert_hapus_produk_permanen')
<style>
    /*
Template Name: Admin Template
Author: Wrappixel

File: scss
*/
@import url(https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700);
/*Theme Colors*/
/**
 * Table Of Content
 *
 * 	1. Color system
 *	2. Options
 *	3. Body
 *	4. Typography
 *	5. Breadcrumbs
 *	6. Cards
 *	7. Dropdowns
 *	8. Buttons
 *	9. Typography
 *	10. Progress bars
 *	11. Tables
 *	12. Forms
 *	14. Component
 */
/*******************
Vertical tabs
******************/
.vtabs {
  display: table; }
  .vtabs .tabs-vertical {
    width: 150px;
    border-bottom: 0px;
    border-right: 1px solid #e9ecef;
    display: table-cell;
    vertical-align: top; }
    .vtabs .tabs-vertical li .nav-link {
      color: #343a40;
      margin-bottom: 10px;
      border: 0px;
      border-radius: 0.25rem 0 0 0.25rem; }
  .vtabs .tab-content {
    display: table-cell;
    padding: 20px;
    vertical-align: top; }

.tabs-vertical li .nav-link.active,
.tabs-vertical li .nav-link:hover,
.tabs-vertical li .nav-link.active:focus {
  background: #fb9678;
  border: 0px;
  color: #fff; }

/*Custom vertical tab*/
.customvtab .tabs-vertical li .nav-link.active,
.customvtab .tabs-vertical li .nav-link:hover,
.customvtab .tabs-vertical li .nav-link:focus {
  background: #fff;
  border: 0px;
  border-right: 2px solid #fb9678;
  margin-right: -1px;
  color: #fb9678; }

.tabcontent-border {
  border: 1px solid #ddd;
  border-top: 0px; }

.customtab2 li a.nav-link {
  border: 0px;
  margin-right: 3px;
  color: #212529; }
  .customtab2 li a.nav-link.active {
    background: #fb9678;
    color: #fff; }
  .customtab2 li a.nav-link:hover {
    color: #fff;
    background: #fb9678; }


/* The container */
.container-check {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container-check input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container-check:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container-check input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container-check input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container-check .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-b-0">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Estimasi</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Real Salses</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#produk" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Produk</span></a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="home2" role="tabpanel">
                        <div class="p-20">
                            {{ Form::open(['url' => '/admin/trash/restore','class'=>'border p-3 delete-permanen-form']) }}
                                <button type="submit" class="btn btn-link" role="submit">Restore</button>
                                <a href="javascript:;" class="btn btn-link btn-link btn-delete-permanen" role="button" style="color: #FF7575;">Delete</a>
                                <input type="hidden" value="restore" name="action" id="trash-action" />

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <table class="table table-bordered table-stripedz myTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>No</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Produk</th>
                                            <th>Nama User</th>
                                            <th>Outlet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($estimasis as $estimasi)
                                            <tr>
                                                <td>
                                                    <label class="container-check">
                                                        <input type="checkbox" value="{{$estimasi->id}}" name="estimasis[]">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $estimasi->bulan }}</td>
                                                <td>{{ $estimasi->tahun }}</td>
                                                <td>{{ $estimasi->nama_produk }}</td>
                                                <td>{{ $estimasi->nama_user }}</td>
                                                <td>{{ $estimasi->nama_outlet }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="tab-pane" id="profile2" role="tabpanel">
                        <div class="p-20">
                                {{ Form::open(['url' => '/admin/trash/restore-real-sales','class'=>'border p-3 delete-permanen-real-sales-form']) }}
                                <button type="submit" class="btn btn-link" role="submit">Restore</button>
                                <a href="javascript:;" class="btn btn-link btn-link btn-delete-real-sales-permanen" role="button" style="color: #FF7575;">Delete</a>
                                <input type="hidden" value="restore" name="action" id="trash-real-sales-action" />

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <table class="table table-bordered table-striped myTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>No</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Produk</th>
                                            <th>Nama User</th>
                                            <th>Outlet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($real_sales as $real_sales)
                                            <tr>
                                                <td>
                                                    <label class="container-check">
                                                        <input type="checkbox" value="{{$real_sales->id}}" name="real_sales[]">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $real_sales->bulan }}</td>
                                                <td>{{ $real_sales->tahun }}</td>
                                                <td>{{ $real_sales->nama_produk }}</td>
                                                <td>{{ $real_sales->nama_user }}</td>
                                                <td>{{ $real_sales->nama_outlet }}</td>
                                            </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="tab-pane" id="produk" role="tabpanel">
                        <div class="p-20">
                            {{ Form::open(['url' => '/admin/trash/restore-produks','class'=>'border p-3 delete-permanen-produks-form']) }}
                                <button type="submit" class="btn btn-link" role="submit">Restore</button>
                                <a href="javascript:;" class="btn btn-link btn-link btn-delete-produks-permanen" role="button" style="color: #FF7575;">Delete</a>
                                <input type="hidden" value="restore" name="action" id="trash-produks-action" />

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Kode</th>
                                            <th>Golongan</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produks as $produk)
                                            <tr>
                                                <td>
                                                    <label class="container-check">
                                                        <input type="checkbox" value="{{$produk->id}}" name="produks[]">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $produk->nama }}</td>
                                                <td>{{ $produk->kode }}</td>
                                                <td>{{ $produk->golongan }}</td>
                                                <td>{{ $produk->harga }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
