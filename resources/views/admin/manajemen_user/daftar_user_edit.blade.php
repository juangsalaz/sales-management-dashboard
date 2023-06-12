@extends('admin.layout._layout')

@section('title', 'Daftar User')

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

.coverage_area_options {
    display: none;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Daftar User</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-user/daftar-user/update/'.$data_user[0]['id'],'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nama_user">Nama User <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_user" name="nama_user" value="{{$data_user[0]['nama_user']}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" value="{{$data_user[0]['username']}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="bagian">Bagian <span class="text-danger">*</span></label>
                            <select name="bagian" id="bagian" class="form-control custom-select" autocomplete="off">
                            @foreach ($bagians as $bagian)
                                @if ($bagian['id']==$data_user[0]['bagian'])
                                <option  value="{{$bagian['id']}}" selected>{{$bagian['nama_bagian']}}</option>
                                @else
                                <option value="{{$bagian['id']}}">{{$bagian['nama_bagian']}}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="password_confirmation">Ulangi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi Password">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan" id="jabatan" class="form-control custom-select jabatan" autocomplete="off">
                            @foreach ($jabatans as $jabatan)
                            @if ($jabatan['id']==$data_user[0]['jabatan'])
                            <?php
                                $selected_nama_jabatan = $jabatan['nama_jabatan'];
                            ?>
                            <option value="{{$jabatan['id']}}" selected>{{$jabatan['nama_jabatan']}}</option>
                            @else
                            <option value="{{$jabatan['id']}}">{{$jabatan['nama_jabatan']}}</option>
                            @endif
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 coverage_area_options">
                            <label for="keterangan">Coverage Area <span class="text-danger">*</span></label>
                            <div class="multiselect">
                                <div class="selectBox" id="select-checkboxes" >
                                <select class="form-control">
                                    <option value="">Pilih Coverage Area</option>
                                </select>
                                <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes" style="height:250px; overflow: scroll;">
                                    <input type="hidden" name="areas[]" class="is_checkbox" value="0" />
                                    <label for="semua-area" class="checkbox-inline">
                                            <input type="checkbox" id="semua-area" /> Pilih Semua</label>
                                    @foreach ($coverage_areas as $area)
                                        @php
                                            $checked = '';
                                        @endphp
                                        @if (!empty($selected_coverage_area))
                                            @foreach ($selected_coverage_area as $dist_area)
                                                @if ($dist_area->coverage_area_id == $area->id)
                                                    @php
                                                        $checked = 'checked';
                                                        break;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                        <label for="{{ $area->id }}" class="checkbox-inline">
                                        <input class="area-checkbox" type="checkbox" id="{{ $area->id }}" name="areas[]" value="{{ $area->id }}" {{$checked}} /> {{ $area->nama_coverage_area }}</label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control custom-select" autocomplete="off">
                                @if ($data_user[0]['status']==1)
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                @else
                                    <option value="1">Aktif</option>
                                    <option value="0" selected>Tidak Aktif</option>
                                @endif

                            </select>
                        </div>
                    </div>
                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('daftar-user')}}" class="btn btn-link" role="button">Batal</a>
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
                                <th>Username</th>
                                <th>Bagian</th>
                                <th>Jabatan</th>
                                <th>Coverage Area</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user['nama_user'] }}</td>
                                    <td>{{ $user['username'] }}</td>
                                    <td>{{ $user['nama_bagian'] }}</td>
                                    <td>{{ $user['nama_jabatan'] }}</td>
                                    <td>
                                        @if (!empty($user['coverage_area']))
                                            @foreach ($user['coverage_area'] as $area)
                                                {{$area->nama_coverage_area}}
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>@if ($user['status']==1)
                                        Aktif
                                    @else
                                        Tidak Aktif
                                    @endif
                                    </td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$user['id']}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$user['id']}}" slug='{{$action->slug}}' nama='{{$user['nama_user']}}' page="user" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
@endsection

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
</script>