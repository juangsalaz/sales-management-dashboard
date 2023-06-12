@extends('admin.layout._layout')

@section('title', 'Daftar Jabatan')

@section('content')
@include('admin.layout._alert_hapus')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @foreach ($data_sub_menu2 as $action)
                    @if ($action->nama =='Tambah')
                        <a href="{{route('daftar-jabatan-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                    @endif
                @endforeach
                <a href="{{route('daftar-jabatan-export')}}" class="btn btn-link" role="button">Export to Excel</a>
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
