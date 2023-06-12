@extends('admin.layout._layout')

@section('title', 'Daftar Produk')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.manajemen_produk._alert_view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Daftar Produk</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-produk/daftar-produk/store', 'files'=>true,'class'=>'border p-3']) }}

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nama">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Produk">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="kode">Kode Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode Produk">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="golongan">Golongan</label>
                        <input type="text" class="form-control" id="golongan" name="golongan" placeholder="Golongan">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="harga">Harga <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="rp-harga">Rp.</span>
                        </div>
                            <input type="text" class="form-control input-no-dec" id="harga" name="harga" placeholder="Harga" aria-label="Harga" aria-describedby="rp-harga">
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="distributor">Distributor <span class="text-danger">*</span></label>
                        <select class="form-control" name="distributor">
                            <option value="">Pilih Distributor</oprion>
                            @foreach ($distributors as $dist)
                                <option value="{{ $dist->id }}">{{ $dist->nama_distributor }}</oprion>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="gambar">Gambar</label>
                        {{-- <input type="file" class="form-control" id="gambar" name="gambar" placeholder="Upload Gambar"> --}}
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar" name="gambar">
                            <label class="custom-file-label" for="gambar">Upload Gambar</label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Stok</label> 
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4" style="margin: 0;">
                        <label for="harga">Coverage Area <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-group col-md-4" style="margin: 0;">
                        <label for="distributor">Cabang <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-group col-md-2" style="margin: 0;">
                        <label for="jumlah">Jumlah <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-group col-md-2">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <select class="form-control select-coverage-area" id="1" name="coverage_areas[]">
                            <option value="0">Pilih Coverage Area</oprion>
                            @foreach ($coverage_areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nama_coverage_area }}</oprion>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <select class="form-control select-cabang-1" name="cabangs[]">
                            <option value="0">Pilih Cabang</oprion>

                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <input type="text" class="form-control" id="jumlah" name="jumlah[]" placeholder="Jumlah Produk">
                    </div>
                    <div class="form-group col-md-2">
                        <button type="button" id="add-stok-cabang" class="btn btn-primary">+</button>
                    </div>
                </div>
                <div id="append-stok"></div>
                {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
                <a href="{{route('daftar-produk')}}" class="btn btn-link" role="button">Batal</a>
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
                                <th>Nama Produk</th>
                                <th>Kode</th>
                                <th>Golongan</th>
                                <th>Harga</th>
                                <th>Distributor</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produks as $produk)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ $produk->kode }}</td>
                                    <td>{{ $produk->golongan }}</td>
                                    <td>{{ number_format($produk->harga,0,',','.') }}</td>
                                    <td>{{ $produk->nama_distributor }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='View')
                                                <a view_id="{{$produk->id}}" nama='{{$produk->nama}}' kode="{{$produk->kode}}" golongan="{{ $produk->golongan }}" harga="{{ $produk->harga }}" distributor="{{ $produk->nama_distributor }}" image="{{ $produk->gambar }}" page="produk" href="javascript:;" style="background-color:#449AFF;" class="view_btn btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/vieww.png') }}">
                                                </a>
                                            @elseif ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$produk->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$produk->id}}" slug='{{$action->slug}}' nama='{{$produk->nama}}' page="produk" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
@endsection
