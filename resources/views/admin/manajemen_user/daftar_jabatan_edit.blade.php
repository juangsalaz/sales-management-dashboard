@extends('admin.layout._layout')

@section('title', 'Daftar Jabatan')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Daftar Jabatan</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-user/daftar-jabatan/update/'.$data_jabatan[0]['id'],'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                            <input type="hidden" id="id" name="id" value="{{$data_jabatan[0]['id']}}">
                        <div class="form-group col-md-4">
                            <label for="nama_jabatan">Nama Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" value="{{$data_jabatan[0]['nama_jabatan']}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" value="{{$data_jabatan[0]['keterangan']}}">
                        </div>
                    </div>
                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('daftar-jabatan')}}" class="btn btn-link" role="button">Batal</a>
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
                                <th>Nama Jabatan</th>
                                <th>Keterangan</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jabatans as $jabatan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $jabatan['nama_jabatan'] }}</td>
                                    <td>{{ $jabatan['keterangan'] }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$jabatan['id']}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$jabatan['id']}}" slug='{{$action->slug}}' nama='{{$jabatan['nama_jabatan']}}' page="jabatan" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
