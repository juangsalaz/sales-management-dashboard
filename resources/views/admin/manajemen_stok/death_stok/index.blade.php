@extends('admin.layout._layout')

@section('title', 'Dead Stok')

@section('content')
@include('admin.layout._alert_hapus')
<style>
    .card-distributor p {
        color: #9E9E9E;
        font-weight: 500;
        font-size: 12px;
        margin: 0 auto;
    }

    .card-distributor h2 {
        color: #444444;
        font-weight: 600;
        margin: 0 auto;
        margin-top: 15px;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @foreach ($data_sub_menu2 as $action)
                    @if ($action->nama =='Tambah')
                        <a href="{{route('death-stok-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                    @endif
                @endforeach
                <a href="{{route('death-stok-export')}}" class="btn btn-link" role="button">Export to Excel</a>
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
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($death_stoks as $death_stok)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $death_stok->nama_produk }}</td>
                                    <td>{{ $death_stok->nama_user }}</td>
                                    <td>{{ $death_stok->coverage_area }}</td>
                                    <td>{{ $death_stok->distributor }}</td>
                                    <td>{{ $death_stok->cabang }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$death_stok->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$death_stok->id}}" slug='{{$action->slug}}' nama='{{$death_stok->nama_user}}' page="Death Stok" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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

<div class="row">
        <div class="col-md-4">
            <div class="card" style="border-radius: 10px 10px 0 0;">
                <div class="card-body card-distributor">
                    <div class="row">
                        <p>TOTAL DEAD STOCK</p>
                    </div>
                    <div class="row">
                        <h2>{{ $total_mbs }}</h2>
                    </div>
                </div>
                <div class="card-footer text-muted" style="background-color: #449AFF; border-radius: 0 0 10px 10px;">
                   <p style="color: white; text-align: center; margin: 0; font-weight: 500;">DISTRIBUTOR MBS</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="border-radius: 10px 10px 0 0;">
                <div class="card-body card-distributor">
                        <div class="row">
                            <p>TOTAL DEAD STOCK</p>
                        </div>
                        <div class="row">
                            <h2>{{ $total_rni }}</h2>
                        </div>
                </div>
                <div class="card-footer text-muted" style="background-color: #F0BD06; border-radius: 0 0 10px 10px;">
                    <p style="color: white; text-align: center; margin: 0; font-weight: 500;">DISTRIBUTOR RNI</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="border-radius: 10px 10px 0 0;">
                <div class="card-body card-distributor">
                        <div class="row">
                            <p>TOTAL DEAD STOCK</p>
                        </div>
                        <div class="row">
                            <h2>{{ $total_igm }}</h2>
                        </div>
                </div>
                <div class="card-footer text-muted" style="background-color: #FF7575; border-radius: 0 0 10px 10px;">
                    <p style="color: white; text-align: center; margin: 0; font-weight: 500;">DISTRIBUTOR IGM</p>
                </div>
            </div>
        </div>
    </div>
@endsection
