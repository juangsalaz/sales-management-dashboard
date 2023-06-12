@extends('admin.layout._layout')

@section('title', 'Data Stok')

@section('content')
@include('admin.layout._alert_hapus')
<style>
    .card-distributor label {
        color: #9E9E9E;
        font-weight: 500;
        font-size: 12px;
    }

    .card-distributor p {
        color: #444444;
        font-weight: 600;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @foreach ($data_sub_menu2 as $action)
                    @if ($action->nama =='Tambah')
                        <a href="{{route('data-stok-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                    @endif
                @endforeach
                <a href="{{route('data-stok-export')}}" class="btn btn-link" role="button">Export to Excel</a>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>User</th>
                                <th>Area</th>
                                <th>Distributor</th>
                                <th>Cabang</th>
                                <th>Kuantiti</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_stoks as $data_stok)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data_stok->nama_produk }}</td>
                                    <td>{{ $data_stok->nama_user }}</td>
                                    <td>{{ $data_stok->coverage_area }}</td>
                                    <td>{{ $data_stok->distributor }}</td>
                                    <td>{{ $data_stok->cabang }}</td>
                                    <td>{{ $data_stok->kuantiti }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='View')
                                                <a href="#" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    view
                                                </a>
                                            @elseif ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$data_stok->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$data_stok->id}}" slug='{{$action->slug}}' nama='{{$data_stok->nama_user}}' page="Data Stok" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
