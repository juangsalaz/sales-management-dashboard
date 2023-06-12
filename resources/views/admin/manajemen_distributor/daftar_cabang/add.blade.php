@extends('admin.layout._layout')

@section('title', 'Daftar Cabang')

@section('content')
@include('admin.layout._alert_hapus')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Daftar Cabang</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-area/daftar-cabang/store','class'=>'border p-3']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="coverage_area">Coverage Area <span class="text-danger">*</span></label>
                            <select name="coverage_area" class="form-control">
                                <option value="">Pilih Coverage Area</option>
                                @foreach($coverage_areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nama_coverage_area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nama_cabang">Nama Cabang <span class="text-danger">*</span></label>
                            {{ Form::text('nama_cabang', null,['class'=>'form-control','placeholder'=>'Nama Cabang']) }}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan">
                        </div>
                    </div>
                    {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
                    <a href="{{route('daftar-cabang')}}" class="btn btn-link" role="button">Batal</a>
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
                                <th>Coverage Area</th>
                                <th>Nama Cabang</th>
                                <th>Keterangan</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cabangs as $cabang)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cabang->nama_coverage_area }}</td>
                                    <td>{{ $cabang->nama_cabang }}</td>
                                    <td>{{ $cabang->keterangan }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama=='Edit')
                                                <a href="{{$action->slug}}/{{$cabang->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama=='Hapus')
                                                <a id="{{$cabang->id}}" slug='{{$action->slug}}' nama='{{$cabang->nama_cabang}}' page="cabang" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
