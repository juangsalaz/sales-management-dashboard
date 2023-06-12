@extends('admin.layout._layout')

@section('title', 'Target Distributor')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.manajemen_marketing.terget_distributor._alert_view')
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
                        <a href="{{route('target-distributor-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                    @endif
                @endforeach
                <a href="{{route('target-distributor-export')}}" class="btn btn-link" role="button">Export to Excel</a>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Distributor</th>
                                <th>DM</th>
                                <th>Area</th>
                                <th>Target</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($target_distributor as $target_dist)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('F', strtotime($target_dist->bulan)) }}</td>
                                    <td>{{ $target_dist->tahun }}</td>
                                    <td>{{ $target_dist->nama_distributor }}</td>
                                    <td>{{ $target_dist->nama_user }}</td>
                                    <td>{{ $target_dist->coverage_area }}</td>
                                    <td>{{ number_format($target_dist->target,0,',','.') }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='View')
                                                <a id="{{$target_dist->id}}" href="javascript:;" style="background-color:#449AFF;" class="view_target_dist_btn btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/vieww.png') }}">
                                                </a>
                                            @elseif ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$target_dist->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$target_dist->id}}" slug='{{$action->slug}}' nama='{{$target_dist->nama_user}}' page="target distributor" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
                    <div class="col-md-4">
                        <label>Distributor MBS</label>
                        <p>{{ number_format($target_mbs,0,',','.') }}</p>
                    </div>
                    <div class="col-md-4">
                        <label>Distributor RNI</label>
                        <p>{{ number_format($target_rni,0,',','.') }}</p>
                    </div>
                    <div class="col-md-4">
                        <label>Distributor IGM</label>
                        <p>{{ number_format($target_igm,0,',','.') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted" style="background-color: #449AFF; border-radius: 0 0 10px 10px;">
            <p style="color: white; text-align: center; margin: 0; font-weight: 500;">TOTAL NILAI TARGET</p>
            </div>
        </div>
    </div>
</div>
@endsection
