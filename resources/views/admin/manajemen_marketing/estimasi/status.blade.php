@extends('admin.layout._layout')

@section('title', 'Estimasi')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')
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
                <h4 class="card-title">Form Edit Status Estimasi</h4>
                {{ Form::open(['url' => '/admin/manajemen-marketing/estimasi/status-update/'.$data_estimasi[0]->id,'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="status">Ubah Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control">
                                @if($data_estimasi[0]->status == 'Diterima')
                                    <option value="Diterima" selected>Diterima</option>
                                    <option value="Ditolak">Ditolak</option>
                                    <option value="Pengajuan">Pengajuan</option>
                                @elseif($data_estimasi[0]->status == 'Ditolak')
                                    <option value="Diterima">Diterima</option>
                                    <option value="Ditolak" selected>Ditolak</option>
                                    <option value="Pengajuan">Pengajuan</option>
                                @elseif ($data_estimasi[0]->status == 'Pengajuan')
                                    <option value="Diterima">Diterima</option>
                                    <option value="Ditolak">Ditolak</option>
                                    <option value="Pengajuan" selected>Pengajuan</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('estimasi')}}" class="btn btn-link" role="button">Batal</a>
                {{-- </form> --}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Produk</th>
                                <th>Nama User</th>
                                <th>Outlet</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estimasis as $estimasi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $estimasi->bulan }}</td>
                                    <td>{{ $estimasi->tahun }}</td>
                                    <td>{{ $estimasi->nama_produk }}</td>
                                    <td>{{ $estimasi->nama_user }}</td>
                                    <td>{{ $estimasi->nama_outlet }}</td>
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
