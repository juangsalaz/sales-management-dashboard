@extends('admin.layout._layout')

@section('title', 'Real Sales')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.manajemen_marketing.real_sales._alert_view')
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
                <h4 class="card-title">Form Real Sales</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-marketing/real-sales/store','class'=>'border p-3']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="bulan">Pilih Bulan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="bulan" placeholder="Pilih Bulan" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Pilih Tahun <span class="text-danger">*</span></label>
                            <input type="text" class="form-control yearpicker" name="tahun" placeholder="Pilih Tahun"/>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="user">DM <span class="text-danger">*</span></label>
                            @if ($jabatan_pengguna == 'Direktur' or $jabatan_pengguna == 'Superuser')
                                <select name="user" id="estimasi-dm" class="form-control js-example-basic-single">
                                    <option value="0">Pilih DM</option>
                                    @foreach ($data_dm as $dm)
                                        <option value="{{$dm->id}}">{{$dm->nama_user}}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="user" id="estimasi-dm" class="form-control js-example-basic-single">
                                    <option value="0">Pilih DM</option>
                                    @foreach ($data_dm as $dm)
                                        @if ($dm->id == Auth::user()->id)
                                            <option value="{{$dm->id}}">{{$dm->nama_user}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="coverage_area">Coverage Area <span class="text-danger">*</span></label>
                            <select name="coverage_area" id="estimasi-coverage-area2" class="form-control js-example-basic-single">
                                <option value="0">Pilih Coverage Area</option>

                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="cabang">Cabang <span class="text-danger">*</span></label>
                            <select name="cabang" id="estimasi-cabang2" class="form-control js-example-basic-single">
                                <option value="0">Pilih Cabang</option>

                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="distributor">Distributor <span class="text-danger">*</span></label>
                            <select name="distributor" id="estimasi-distributor2" class="form-control js-example-basic-single">
                                <option value="0">Pilih Distributor</option>

                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                    <div class="form-group col-md-4">
                            <label for="tahun">Pilih Outlet Transaksi <span class="text-danger">*</span></label>
                            <select name="outlet" class="form-control js-example-basic-single">
                                <option value="0">Pilih Outlet</option>
                                @foreach($outlets as $outlet)
                                    <option value="{{$outlet->id}}">{{$outlet->nama_outlet}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="produk">Produk <span class="text-danger">*</span></label>
                            <select name="produk" id="select-product" class="form-control js-example-basic-single" harga-produk="this.options[this.selectedIndex].getAttribute('isred')">
                                <option value="0">Pilih Produk</option>

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="harga_produk">Harga Produk <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-harga">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="harga_produk" id="harga-produk" placeholder="Harga Produk" aria-label="Harga" aria-describedby="rp-harga" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tahun">Kuantiti <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-no-dec" name="kuantiti" id="kuantiti-input" placeholder="Kuantiti" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Value Gross <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-value-gross">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="value_gross" id="value-gross" placeholder="Value Gross" aria-label="Value Gross" aria-describedby="rp-value-gross" readonly/>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Diskon <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control input-diskon" name="diskon" id="diskon-input" aria-label="Diskon" aria-describedby="persen-disokn" placeholder="Diskon" />
                                <div class="input-group-append">
                                    <span class="input-group-text" id="persen-disokn">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tahun">Diskon Value <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-diskon-value">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="diskon_value" id="diskon-value" placeholder="Diskon Value" aria-label="Diskon Value" aria-describedby="rp-diskon-value" readonly />
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Value Nett <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="rp-value-nett">Rp.</span>
                            </div>
                                <input type="text" class="form-control input-no-dec" name="value_nett" id="value-nett" placeholder="Value Nett" aria-label="Value Nett" aria-describedby="rp-value-nett" readonly />
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Status <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="status" placeholder="Status" value="Pengajuan" readonly />
                        </div>
                    </div>
                    {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
                    <a href="{{route('realsales')}}" class="btn btn-link" role="button">Batal</a>
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
                           @foreach($real_sales as $real_sales)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('F', strtotime($real_sales->bulan)) }}</td>
                                    <td>{{ $real_sales->tahun }}</td>
                                    <td>{{ $real_sales->nama_produk }}</td>
                                    <td>{{ $real_sales->nama_user }}</td>
                                    <td>{{ $real_sales->nama_outlet }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='View')
                                                <a id="{{$real_sales->id}}" href="javascript:;" style="background-color:#449AFF;" class="view_real_sales_btn btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/vieww.png') }}">
                                                </a>
                                            @elseif ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$real_sales->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$real_sales->id}}" slug='{{$action->slug}}' nama='{{$real_sales->nama_user}}' page="real sales" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
                                                    <img style="width:16px;vertical-align:middle;" src="{{ URL::asset('images/icon/deletew.png') }}">
                                                </a>
                                            @elseif($action->nama =='Status')
                                                @if ($real_sales->status!='Diterima')
                                                <a href="{{$action->slug}}/{{$real_sales->id}}" style="background-color:#00D770;" class="btn rounded p-1">
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
