@extends('admin.layout._layout')

@section('title', 'Estimasi')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.manajemen_marketing.estimasi._alert_view')
<style>
    .card-distributor label {
        color: #9E9E9E;
        font-weight: 500;
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
                <div class="row">
                    <div class="col-md-4" style="margin-bottom: 10px;">
                        @foreach ($data_sub_menu2 as $action)
                            @if ($action->nama =='Tambah')
                                <a href="{{route('estimasi-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                            @endif
                        @endforeach
                        <a href="{{route('estimasi-export')}}" class="btn btn-link" role="button">Export to Excel</a>
                    </div>
                    <div class="col-md-8">
                        <form action="{{route('estimasi')}}" method="POST" class="form-inline" style="float:right" autocomplete="off">@csrf
                        @if ($jabatan_pengguna=="Superuser"||$jabatan_pengguna=="Direktur")
                            <select name="user_dm" class="form-control js-example-basic-single">
                                <option value="">Semua DM</option>
                                @foreach ($data_dm as $dm)
                                <option value="{{$dm->id}}">{{$dm->nama_user}}</option>
                                @endforeach
                            </select>
                        @endif

                            <input type="text" class="form-control datepicker" name="bulan" placeholder="Pilih Bulan" style="margin-left: 5px;" />

                            <input type="text" class="form-control yearpicker" name="tahun" placeholder="Pilih Tahun" style="margin-left: 5px;" />

                            {{ Form::submit('Submit',['class'=>'btn btn-primary','style'=>'margin-left:5px;']) }}
                        </form>
                    </div>
                </div>

                    <form action="{{route('estimasi-update-status')}}" method="post">@csrf
                        <div class="row" style="margin-top: 40px;">
                            @if ($jabatan_pengguna=="Superuser"||$jabatan_pengguna=="Direktur")
                                <div class="col-md-3">
                                    <select class="form-control" name="status_update">
                                        <option value="">Pilih Status Update</option>
                                        <option value="1">Diterima</option>
                                        <option value="0">Ditolak</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            @endif
                        </div>
                        <div class="table-responsive m-t-40">
                            <table id="myTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if ($jabatan_pengguna=="Superuser"||$jabatan_pengguna=="Direktur")
                                            <th class="no-sort">
                                                <input type="checkbox" class="form-control" style="height: 20px; width: 20px;" id="check-notif-all">
                                            </th>
                                        @endif
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Produk</th>
                                        <th>Nama User</th>
                                        <th>Outlet</th>
                                        <th>Status</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estimasis as $estimasi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @if ($jabatan_pengguna=="Superuser"||$jabatan_pengguna=="Direktur")
                                                <td>
                                                    <input type="checkbox" class="form-control notif-check" name="notif[]" value="{{ $estimasi->id }}">
                                                </td>
                                            @endif
                                            <td>{{ date('F', strtotime($estimasi->bulan)) }}</td>
                                            <td>{{ $estimasi->tahun }}</td>
                                            <td>{{ $estimasi->nama_produk }}</td>
                                            <td>{{ $estimasi->nama_user }}</td>
                                            <td>{{ $estimasi->nama_outlet }}</td>
                                            <td>{{ $estimasi->status }}</td>
                                            <td>
                                                @foreach ($data_sub_menu2 as $action)
                                                    @if ($action->nama =='View')
                                                        <a id="{{$estimasi->id}}" href="javascript:;" style="background-color:#449AFF;" class="view_estimasi_btn btn rounded p-1">
                                                            <img style="width:16px;" src="{{ URL::asset('images/icon/vieww.png') }}">
                                                        </a>
                                                    @elseif ($action->nama =='Edit')
                                                        <a href="{{$action->slug}}/{{$estimasi->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                            <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                        </a>
                                                    @elseif($action->nama =='Hapus')
                                                        <a id="{{$estimasi->id}}" slug='{{$action->slug}}' nama='{{$estimasi->nama_user}}' page="estimasi" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
                                                            <img style="width:16px;vertical-align:middle;" src="{{ URL::asset('images/icon/deletew.png') }}">
                                                        </a>
                                                    @elseif($action->nama =='Status')
                                                        @if ($estimasi->status!='Diterima')
                                                            <a href="{{$action->slug}}/{{$estimasi->id}}" style="background-color:#00D770;" class="btn rounded p-1">
                                                                <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
        <div class="col-md-4">
            <div class="card" style="border-radius: 10px 10px 0 0;">
                <div class="card-body card-distributor">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Total Produk</label>
                            <p>{{ $total_rni['total_product'] }} Produk</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Value Nett</label>
                            <p>{{ number_format($total_rni['total_value_nett'],0,',','.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Value Gross</label>
                            <p>{{ number_format($total_rni['total_value_gross'],0,',','.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Diskon Rata - Rata</label>
                            <p>{{ $total_rni['rata_diskon'] }}%</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Diskon Value</label>
                            <p>{{ number_format($total_rni['total_diskon_value'],0,',','.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted" style="background-color: #449AFF; border-radius: 0 0 10px 10px;">
                   <p style="color: white; text-align: center; margin: 0; font-weight: 500;">DISTRIBUTOR RNI</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="border-radius: 10px 10px 0 0;">
                <div class="card-body card-distributor">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Total Produk</label>
                            <p>{{ $total_mbs['total_product'] }} Produk</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Value Nett</label>
                            <p>{{ number_format($total_mbs['total_value_nett'],0,',','.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Value Gross</label>
                            <p>{{ number_format($total_mbs['total_value_gross'],0,',','.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Diskon Rata - Rata</label>
                            <p>{{ $total_mbs['rata_diskon'] }}%</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Diskon Value</label>
                            <p>{{ number_format($total_mbs['total_diskon_value'],0,',','.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted" style="background-color: #F0BD06; border-radius: 0 0 10px 10px;">
                    <p style="color: white; text-align: center; margin: 0; font-weight: 500;">DISTRIBUTOR MBS</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="border-radius: 10px 10px 0 0;">
                <div class="card-body card-distributor">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Total Produk</label>
                            <p>{{ $total_igm['total_product'] }} Produk</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Value Nett</label>
                            <p>{{ number_format($total_igm['total_value_nett'],0,',','.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Value Gross</label>
                            <p>{{ number_format($total_igm['total_value_gross'],0,',','.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Diskon Rata - Rata</label>
                            <p>{{ $total_igm['rata_diskon'] }}%</p>
                        </div>
                        <div class="col-md-6">
                            <label>Total Diskon Value</label>
                            <p>{{ number_format($total_igm['total_diskon_value'],0,',','.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted" style="background-color: #FF7575; border-radius: 0 0 10px 10px;">
                    <p style="color: white; text-align: center; margin: 0; font-weight: 500;">DISTRIBUTOR IGM</p>
                </div>
            </div>
        </div>
    </div>
@endsection
