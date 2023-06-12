@extends('admin.layout._layout')

@section('title', 'Daftar Bagian')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Daftar Bagian</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-user/daftar-bagian/update/'.$data_bagian[0]['id'],'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                            <input type="hidden" id="id" name="id" value="{{$data_bagian[0]['id']}}">
                        <div class="form-group col-md-4">
                            <label for="nama_bagian">Nama Bagian <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_bagian" name="nama_bagian" value="{{$data_bagian[0]['nama_bagian']}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" value="{{$data_bagian[0]['keterangan']}}">
                        </div>
                    </div>
                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('daftar-bagian')}}" class="btn btn-link" role="button">Batal</a>
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
                                <th>Nama Bagian</th>
                                <th>Keterangan</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bagians as $bagian)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bagian['nama_bagian'] }}</td>
                                    <td>{{ $bagian['keterangan'] }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$bagian['id']}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$bagian['id']}}" slug='{{$action->slug}}' nama='{{$bagian['nama_bagian']}}' page="bagian" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
