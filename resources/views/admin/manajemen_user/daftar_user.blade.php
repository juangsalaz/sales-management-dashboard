@extends('admin.layout._layout')

@section('title', 'Daftar User')

@section('content')
@include('admin.layout._alert_hapus')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @foreach ($data_sub_menu2 as $action)
                    @if ($action->nama =='Tambah')
                        <a href="{{route('daftar-user-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                    @endif
                @endforeach
                <a href="{{route('daftar-user-export')}}" class="btn btn-link" role="button">Export to Excel</a>
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
