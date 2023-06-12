@extends('admin.layout._layout')

@section('title', 'Modul Pengingat')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 30px;
    width: 30px;
    left: 4px;
    bottom: -7px;
    background-color: #eee;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 10px!important;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Modul Pengingat</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/pengingat/update/'.$data_pengingat[0]->id,'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="judul">Judul Pengingat <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" placeholder="Ketik Judul Pengingat" value="{{ $data_pengingat[0]->pengingat }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" value="{{ $data_pengingat[0]->keterangan }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tanggal">Pilih Tanggal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control tanggal-pengingat" name="tanggal" placeholder="Pilih Tanggal" value="{{ date("d-m-Y", strtotime($data_pengingat[0]->tanggal)) }}"/>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="jam">Pilih Jam <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" name="jam" data-target="#datetimepicker3" placeholder="Pilih Jam" value="{{ $data_pengingat[0]->jam }}"//>
                                    <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('pengingat')}}" class="btn btn-link" role="button">Batal</a>
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
                                <th>Judul Pengingat</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengingats as $pengingat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pengingat->pengingat }}</td>
                                    <td>{{ date("d M Y", strtotime($pengingat->tanggal)) }}</td>
                                    <td>{{ $pengingat->jam }}</td>
                                    <td>
                                        <a href="{{route('pengingat-edit', $pengingat->id)}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                            <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                        </a>

                                        <a id="{{$pengingat->id}}" slug="pengingat/delete" nama='{{$pengingat->pengingat}}' page="pengingat" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
                                            <img style="width:16px;vertical-align:middle;" src="{{ URL::asset('images/icon/deletew.png') }}">
                                        </a>
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
