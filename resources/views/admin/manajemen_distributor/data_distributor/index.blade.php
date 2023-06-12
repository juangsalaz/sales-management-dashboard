@extends('admin.layout._layout')

@section('title', 'Data Distributor')

@section('content')
@include('admin.layout._alert_hapus')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @foreach ($data_sub_menu2 as $action)
                    @if ($action->nama =='Tambah')
                        <a href="{{route('data-distributor-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                    @endif
                @endforeach
                <a href="{{route('data-distributor-export')}}" class="btn btn-link" role="button">Export to Excel</a>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_distributors as $dist)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dist->nama_user }}</td>
                                    <td>
                                        {{ $dist->nama_coverage_area }}
                                    </td>
                                    <td>
                                        {{ $dist->nama_distributor }}
                                    </td>
                                    <td>
                                        {{ $dist->nama_cabang }}
                                    </td>
                                    <td>
                                        {{ $dist->nama_produk }}
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
