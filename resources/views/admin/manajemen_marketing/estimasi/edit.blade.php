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
    .select2-container .select2-selection{
        height: 38px!important;
        padding-top: 5px;
        border-color: #E9ECEF;
    }
    .select2-container .select2-selection__arrow{
        top:5px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        color: #6c757d;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Estimasi</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-marketing/estimasi/update/'.$data_estimasi[0]->id,'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="bulan">Pilih Bulan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="bulan" placeholder="Pilih Bulan" value="{{ date('F', strtotime($data_estimasi[0]->bulan)) }}" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Pilih Tahun <span class="text-danger">*</span></label>
                            <input type="text" class="form-control yearpicker" name="tahun" placeholder="Pilih Tahun" value="{{ $data_estimasi[0]->tahun }}"/>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="user">DM <span class="text-danger">*</span></label>
                            <select name="user" id="estimasi-dm" class="form-control js-example-basic-single">
                                <option value="0">Pilih DM</option>
                                @if ($jabatan_pengguna == 'Direktur' or $jabatan_pengguna == 'Superuser')
                                    @foreach ($data_dm as $dm)
                                        @if($data_estimasi[0]->nama_user == $dm->id)
                                            <option value="{{$dm->id}}" selected>{{$dm->nama_user}}</option>
                                        @else
                                            <option value="{{$dm->id}}">{{$dm->nama_user}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($data_dm as $dm)
                                        @if($data_estimasi[0]->nama_user == $dm->id)
                                            <option value="{{$dm->id}}" selected>{{$dm->nama_user}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="coverage_area">Coverage Area <span class="text-danger">*</span></label>
                            <select name="coverage_area" id="estimasi-coverage-area2" class="form-control js-example-basic-single">
                                @foreach ($coverage_area_edit as $row)
                                    @if ($row->id == $data_estimasi[0]->coverage_area_id)
                                        <option value="{{$row->id}}" class="list-estimasi2">{{$row->nama_coverage_area}}</option>
                                    @else
                                        <option value="{{$row->id}}" class="list-estimasi2">{{$row->nama_coverage_area}}</option>
                                    @endif
                                @endforeach
                                    <option value="0">Pilih Coverage Area</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="cabang">Cabang <span class="text-danger">*</span></label>
                            <select name="cabang" id="estimasi-cabang2" class="form-control js-example-basic-single">
                                @foreach ($cabangs as $cabang)
                                    @if ($cabang->id == $data_estimasi[0]->cabang_id)
                                        <option value="{{$cabang->id}}" class="list-cabang" selected>{{$cabang->nama_cabang}}</option>
                                    @else
                                        <option value="{{$cabang->id}}" class="list-cabang">{{$cabang->nama_cabang}}</option>
                                    @endif

                                @endforeach
                                <option value="0">Pilih Cabang</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="distributor">Distributor <span class="text-danger">*</span></label>
                            <select name="distributor" id="estimasi-distributor2" class="form-control js-example-basic-single">
                                <option value="0">Pilih Distributor</option>
                                @foreach ($distributors as $dist)
                                    @if ($dist->id == $data_estimasi[0]->distributor_id)
                                        <option value="{{$dist->id}}" class="list-distributor" selected>{{$dist->nama_distributor}}</option>
                                    @else
                                        <option value="{{$dist->id}}" class="list-distributor">{{$dist->nama_distributor}}</option>
                                    @endif

                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tahun">Pilih Outlet Transaksi <span class="text-danger">*</span></label>
                            <select name="outlet" class="form-control js-example-basic-single">
                                <option value="0">Pilih Outlet</option>
                                @foreach($outlets as $outlet)
                                    @if ($outlet->id == $data_estimasi[0]->outlet)
                                        <option value="{{$outlet->id}}" selected>{{$outlet->nama_outlet}}</option>
                                    @else
                                        <option value="{{$outlet->id}}">{{$outlet->nama_outlet}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Pilih Produk <span class="text-danger">*</span></label>
                            <select name="produk" id="select-product" class="form-control js-example-basic-single" harga-produk="this.options[this.selectedIndex].getAttribute('isred')">
                                <option value="">Pilih Produk</option>
                                @foreach($produks as $produk)
                                    @if($data_estimasi[0]->produk == $produk->id)
                                        <option value="{{$produk->id}}" harga="{{$produk->harga}}" class="list-produk" selected>{{$produk->nama}}</option>
                                    @else
                                        <option value="{{$produk->id}}" harga="{{$produk->harga}}" class="list-produk">{{$produk->nama}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="harga_produk">Harga Produk <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-harga">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="harga_produk" id="harga-produk" placeholder="Harga Produk" aria-label="Harga" aria-describedby="rp-harga" readonly value="{{ $data_estimasi[0]->harga_produk }}"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tahun">Kuantiti <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-no-dec" name="kuantiti" id="kuantiti-input" placeholder="Kuantiti" value="{{ $data_estimasi[0]->kuantiti }}"/>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Value Gross <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-value-gross">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="value_gross" id="value-gross" placeholder="Value Gross" aria-label="Value Gross" aria-describedby="rp-value-gross" readonly value="{{ $data_estimasi[0]->value_gross }}"/>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Diskon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-diskon" name="diskon" id="diskon-input" placeholder="Diskon" value="{{ $data_estimasi[0]->diskon }}"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tahun">Diskon Value <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-diskon-value">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="diskon_value" id="diskon-value" placeholder="Diskon Value" aria-label="Diskon Value" aria-describedby="rp-diskon-value" readonly value="{{ $data_estimasi[0]->diskon_value }}"/>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Value Nett <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-value-nett">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="value_nett" id="value-nett" placeholder="Value Nett" aria-label="Value Nett" aria-describedby="rp-value-nett" readonly value="{{ $data_estimasi[0]->value_nett }}"/>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Status <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="status" placeholder="Status" value="Pengajuan" readonly value="{{ $data_estimasi[0]->status }}"/>
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
                                    <td>{{ date('F', strtotime($estimasi->bulan)) }}</td>
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
