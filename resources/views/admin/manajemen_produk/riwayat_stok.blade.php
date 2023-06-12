@extends('admin.layout._layout')

@section('title', 'Riwayat Stok')

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
                                <th>Cabang</th>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Jumlah</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat_stoks as $riwayat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $riwayat->nama_produk }}</td>
                                    <td>{{ $riwayat->nama_cabang }}</td>
                                    <td>{{ $riwayat->created_at }}</td>
                                    <td>{{ $riwayat->nama_user }}</td>
                                    <td>{{ $riwayat->kuantiti }}</td>
                                    <td>{{ $riwayat->type }}</td>
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
