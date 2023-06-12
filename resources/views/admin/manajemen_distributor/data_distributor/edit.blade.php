@extends('admin.layout._layout')

@section('title', 'Data Distributor')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')
<style>
.multiselect {
  width: auto;
}

.selectBox {
  position: relative;
}

.selectBox select {
  width: 100%;
}

.overSelect {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}

#checkboxes, #checkboxesdist, #checkboxescabang, #checkboxesproduk {
  display: none;
  border: 1px #dadada solid;
  position: absolute;
    background: rgb(238, 238, 238);
    z-index: 1;
    width: 97%;
}


#checkboxes label, #checkboxesdist label, #checkboxescabang label, #checkboxesproduk label {
  display: block;
  padding-left: 10px;
}

#checkboxes label:hover, #checkboxesdist label:hover, #checkboxescabang label:hover, #checkboxesproduk label:hover {
  background-color: #4499FF;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Daftar Data Distributor</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-distributor/data-distributor/update/'.$data_distributor[0]['id'],'class'=>'border p-3 edit-form']) }}
                <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nama_cabang">User <span class="text-danger">*</span></label>
                            <select class="form-control" name="user">
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    @if ($data_distributor[0]->users == $user->id)
                                        <option value="{{ $user->id }}" selected>{{ $user->nama_user }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="keterangan">Coverage Area <span class="text-danger">*</span></label>
                            <div class="multiselect">
                                <div class="selectBox" id="select-checkboxes">
                                <select class="form-control">
                                    <option value="">Pilih Coverage Area</option>
                                </select>
                                <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes" style="height:250px; overflow: scroll;">
                                    <label for="semua-area" class="checkbox-inline">
                                            <input type="checkbox" id="semua-area" /> Pilih Semua</label>
                                    @php
                                        $checked = '';
                                    @endphp
                                    @foreach ($coverage_areas as $area)
                                        @foreach ($data_distributor_area_selected as $dist_area)
                                            @php
                                                $checked = '';
                                            @endphp
                                            @if ($dist_area->coverage_area_id == $area->id)
                                                @php
                                                    $checked = 'checked';
                                                    break;
                                                @endphp
                                            @endif
                                        @endforeach
                                        <label for="{{ $area->id }}" class="checkbox-inline">
                                        <input class="area-checkbox" type="checkbox" id="{{ $area->id }}" name="areas[]" value="{{ $area->id }}" {{$checked}} /> {{ $area->nama_coverage_area }}</label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nama_cabang">Distributor <span class="text-danger">*</span></label>

                            <div class="multiselect">
                                <div class="selectBox" id="select-checkboxesdist">
                                <select class="form-control">
                                    <option value="">Pilih Distributor</option>
                                </select>
                                <div class="overSelect"></div>
                                </div>
                                <div id="checkboxesdist">
                                    <label for="semua-dist" class="checkbox-inline">
                                            <input type="checkbox" id="semua-dist" /> Pilih Semua</label>
                                    @php
                                        $checked = '';
                                    @endphp
                                    @foreach ($distributors as $distributor)
                                        @foreach ($data_distributor_distributor_selected as $dist_distributor)
                                            @php
                                                $checked = '';
                                            @endphp
                                            @if ($dist_distributor->distributor_id == $distributor->id)
                                                @php
                                                    $checked = 'checked';
                                                    break;
                                                @endphp
                                            @endif
                                        @endforeach
                                        <label for="{{ $distributor->id }}" class="checkbox-inline">
                                            <input class="dist-checkbox" type="checkbox" id="{{ $distributor->id }}" name="distributors[]" value="{{ $distributor->id }}" {{$checked}}/> {{ $distributor->nama_distributor }}</label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nama_cabang">Cabang <span class="text-danger">*</span></label>

                                <div class="multiselect">
                                    <div class="selectBox" id="select-checkboxescabang">
                                    <select class="form-control">
                                        <option value="">Pilih Cabang</option>
                                    </select>
                                    <div class="overSelect"></div>
                                    </div>
                                    <div id="checkboxescabang" style="height:250px; overflow: scroll;">
                                        <label for="semua-cabang" class="checkbox-inline">
                                            <input type="checkbox" id="semua-cabang" /> Pilih Semua</label>
                                        @php
                                            $checked = '';
                                        @endphp
                                        @foreach ($cabangs as $cabang)
                                            @foreach ($data_distributor_cabang_selected as $dist_cabang)
                                                @php
                                                    $checked = '';
                                                @endphp
                                                @if ($dist_cabang->cabang_id == $cabang->id)
                                                    @php
                                                        $checked = 'checked';
                                                        break;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <label for="{{ $cabang->id }}" class="checkbox-inline">
                                                <input class="cabang-checkbox" type="checkbox" id="{{ $cabang->id }}" name="cabangs[]" value="{{ $cabang->id }}" {{$checked}}/> {{ $cabang->nama_cabang }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="nama_cabang">Produk <span class="text-danger">*</span></label>

                                <div class="multiselect">
                                    <div class="selectBox" id="select-checkboxesproduk">
                                    <select class="form-control">
                                        <option value="">Pilih Produk</option>
                                    </select>
                                    <div class="overSelect"></div>
                                    </div>
                                    <div id="checkboxesproduk" style="height:250px; overflow: scroll;">
                                        <label for="semua-produk" class="checkbox-inline">
                                            <input type="checkbox" id="semua-produk" /> Pilih Semua</label>
                                        @php
                                            $checked = '';
                                        @endphp
                                        @foreach ($produks as $produk)
                                            @foreach ($data_distributor_produk_selected as $dist_produk)
                                                @php
                                                    $checked = '';
                                                @endphp
                                                @if ($dist_produk->produk_id == $produk->id)
                                                    @php
                                                        $checked = 'checked';
                                                        break;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <label for="{{ $produk->id }}" class="checkbox-inline">
                                                <input class="produk-checkbox" type="checkbox" id="{{ $produk->id }}" name="produks[]" value="{{ $produk->id }}" {{$checked}}/> {{ $produk->nama }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                    </div>
                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('data-distributor')}}" class="btn btn-link" role="button">Batal</a>
                {{-- </form> --}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive m-t-40">
                <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Coverage Area</th>
                                <th>Distributor</th>
                                <th>Cabang</th>
                                <th>Produk</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_distributors as $dist)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dist->nama_user }}</td>
                                    <td>
                                        @foreach ($data_distributor_area as $dist_area)
                                            @if ($dist_area->data_distributor_id == $dist->id)
                                                {{$dist_area->nama_coverage_area}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($data_distributor_distributor as $dist_distributor)
                                            @if ($dist_distributor->data_distributor_id == $dist->id)
                                                {{$dist_distributor->nama_distributor}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($data_distributor_cabang as $dist_cabang)
                                            @if ($dist_cabang->data_distributor_id == $dist->id)
                                                {{$dist_cabang->nama_cabang}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($data_distributor_produk as $dist_produk)
                                            @if ($dist_produk->data_distributor_id == $dist->id)
                                                {{$dist_produk->nama_produk}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$dist->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$dist->id}}" slug='{{$action->slug}}' nama='{{$dist->nama_user}}' page="data distributor" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
                                                    <img style="width:16px;vertical-align:middle;" src="{{ URL::asset('images/icon/deletew.png') }}">
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (!expanded) {
    checkboxes.style.display = "block";
    expanded = true;
  } else {
    checkboxes.style.display = "none";
    expanded = false;
  }
}

var expanded_dist = false;

function showCheckboxesDist() {
  var checkboxesdist = document.getElementById("checkboxesdist");
  if (!expanded_dist) {
    checkboxesdist.style.display = "block";
    expanded_dist = true;
  } else {
    checkboxesdist.style.display = "none";
    expanded_dist = false;
  }
}

var expanded_cabang = false;

function showCheckboxesCabang() {
  var checkboxescabang = document.getElementById("checkboxescabang");
  if (!expanded_cabang) {
    checkboxescabang.style.display = "block";
    expanded_cabang = true;
  } else {
    checkboxescabang.style.display = "none";
    expanded_cabang = false;
  }
}

var expanded_produk = false;

function showCheckboxesProduk() {
  var checkboxesproduk = document.getElementById("checkboxesproduk");
  if (!expanded_produk) {
    checkboxesproduk.style.display = "block";
    expanded_produk = true;
  } else {
    checkboxesproduk.style.display = "none";
    expanded_produk = false;
  }
}
</script>
@endsection
