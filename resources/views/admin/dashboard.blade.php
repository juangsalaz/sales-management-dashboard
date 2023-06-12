@extends('admin.layout._layout')

@section('title', 'Dashboard')

@section('content')
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
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Real Sales By Estimasi (<span id="user-achivement"></span>)</h4>
                            <div>
                                <canvas id="sales_by_estimate_dashboard" height="150"> </canvas>
                            </div>
                            <h3 class="card-title" style="text-align: center; margin-top: 20px;" id="real_sales_by_estimasi_prosentase_dashboard"></h3>
                        </div>
                    </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Produck Stock</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Distributor</label>
                                <select class="form-control" id="filter-dist">
                                    <option>Pilih Distributor</option>
                                    @foreach($distributors as $dist)
                                        <option value="{{$dist->id}}">{{$dist->nama_distributor}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="stok-produk"></div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Produck Stock</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Distributor</label>
                                <select class="form-control" id="filter-dist">
                                    <option>Pilih Distributor</option>
                                    @foreach($distributors as $dist)
                                        <option value="{{$dist->id}}">{{$dist->nama_distributor}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <canvas id="chart3" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Real Sales</h4>
                <div>
                    <canvas id="real-sales-chart" height="150"></canvas>
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
                    <p>TOTAL PRODUK</p>
                </div>
                <div class="row">
                    <h2>{{ $total_mbs_product }}</h2>
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
                        <p>TOTAL PRODUK</p>
                    </div>
                    <div class="row">
                        <h2>{{ $total_rni_product }}</h2>
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
                        <p>TOTAL PRODUK</p>
                    </div>
                    <div class="row">
                        <h2>{{ $total_igm_product }}</h2>
                    </div>
            </div>
            <div class="card-footer text-muted" style="background-color: #FF7575; border-radius: 0 0 10px 10px;">
                <p style="color: white; text-align: center; margin: 0; font-weight: 500;">DISTRIBUTOR IGM</p>
            </div>
        </div>
    </div>
</div>
@endsection



