@extends('admin.layout._layout')

@section('title', 'Dead Stock')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- <a href="{{route('daftar-produk-export')}}" class="btn btn-link" role="button">Export to Excel</a> -->
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Coverage Area</th>
                                <th>Cabang</th>
                                <th>Distributor</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dead_stock as $dead)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dead->nama_produk }}</td>
                                    <td>{{ $dead->nama_coverage_area }}</td>
                                    <td>{{ $dead->nama_cabang }}</td>
                                    <td>{{ $dead->nama_distributor }}</td>
                                    <td>{{ $dead->jumlah }}</td>
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
