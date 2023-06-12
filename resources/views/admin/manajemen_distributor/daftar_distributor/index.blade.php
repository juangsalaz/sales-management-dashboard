@extends('admin.layout._layout')

@section('title', 'Daftar Distributor')

@section('content')
@include('admin.layout._alert_hapus')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- <a href="" class="btn btn-link" role="button">Export to Excel</a> --}}
                <div class="table-responsive m-t-20">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Distributor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($distributors as $distributor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $distributor['nama_distributor'] }}</td>
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
