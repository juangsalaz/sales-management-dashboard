@extends('admin.layout._layout')

@section('title', 'Laporan')

@section('content')
@include('admin.layout._alert_hapus')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                    <div class="form-row">
                        @if($nama_jabatan == 'Direktur' or $nama_jabatan == 'Superuser')
                            <div class="form-group col-md-2">
                                <label for="user">User<span class="text-danger">*</span></label>
                                <select class="form-control" name="user" id="user">
                                    <option value="">Pilih User</option>
                                    <option value="all">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="user" id="user" value="{{ $id_user }}"/>
                        @endif

                        <div class="form-group col-md-4">
                            <div class="input-daterange input-group" id="datepickerrange">
                                <div class="col-md-6">
                                    <label for="date">Dari</label>
                                    <input type="text" class="input-sm form-control" name="start" id="start" />
                                </div>
                                  <div class="col-md-6">
                                    <label for="date">Sampai</label>
                                    <input type="text" class="input-sm form-control" name="end" id="end" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="distributor">Distributor <span class="text-danger">*</span></label>
                            <select class="form-control" id="distributor" name="distributor">
                                <option value="">Pilih Distributor</option>
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}">{{ $distributor->nama_distributor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-primary" id="filter-submit" style="margin-top: 29px;">Submit</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <canvas id="realsales_by_estimasi_laporan" height="250"> </canvas>
                </div>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;" id="realsales_by_estimasi_laporan_prosentase"></h5>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;">Real Sales By Estimasi</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <canvas id="realsales_by_target" height="250"> </canvas>
                </div>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;" id="realsales_by_target_laporan_prosentase"></h5>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;">Real Sales by Target</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <canvas id="diskon_rata_by_dist" height="250"> </canvas>
                </div>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;" id="diskon_rata_by_dist_laporan_prosentase"></h5>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;">Diskon Rata-Rata by Distributor</h5>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <canvas id="realsales_diskon_value_by_estimasi_diskon_value" height="250"> </canvas>
                </div>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;" id="realsales_diskon_value_by_estimasi_diskon_value_prosentase"></h5>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;">Total Real Sales by Total Estimasi Diskon Value</h5>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <canvas id="realsales_value_nett_by_estimasi_value_nett" height="250"> </canvas>
                </div>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;" id="realsales_value_nett_by_estimasi_value_nett_prosentase"></h5>
                <h5 class="card-title" style="text-align: center; margin-top: 20px;">Total Real Sales by Total Estimasi Value Nett</h5>
            </div>
        </div>
    </div>
</div>
@endsection