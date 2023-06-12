@extends('admin.layout._layout')

@section('title', 'Data Stok')

@section('content')
@include('admin.layout._alert_hapus')
<style>
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
                <h4 class="card-title">Form Data Stok</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-stok/data-stok/store','class'=>'border p-3']) }}
                    <div class="form-row">
                    <div class="form-group col-md-4">
                            <label for="produk">Pilih Produk <span class="text-danger">*</span></label>
                            <select name="produk" class="form-control js-example-basic-single">
                                <option value="">Pilih Produk</option>
                                @foreach($produks as $produk)
                                    <option value="{{$produk->id}}">{{$produk->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="kuantiti">Kuantiti Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kuantiti_produk" placeholder="Masukkan Angka" />
                        </div>
                    </div>
                    {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
                    <a href="{{route('data-stok')}}" class="btn btn-link" role="button">Batal</a>
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
                                <th>Produk</th>
                                <th>User</th>
                                <th>Area</th>
                                <th>Distributor</th>
                                <th>Cabang</th>
                                <th>Kuantiti</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data_stoks as $data_stok)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data_stok->nama_produk }}</td>
                                    <td>{{ $data_stok->nama_user }}</td>
                                    <td>{{ $data_stok->coverage_area }}</td>
                                    <td>{{ $data_stok->distributor }}</td>
                                    <td>{{ $data_stok->cabang }}</td>
                                    <td>{{ $data_stok->kuantiti }}</td>
                                    <td>
                                        @foreach ($data_sub_menu2 as $action)
                                            @if ($action->nama =='View')
                                                <a href="#" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    view
                                                </a>
                                            @elseif ($action->nama =='Edit')
                                                <a href="{{$action->slug}}/{{$data_stok->id}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                                    <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                                </a>
                                            @elseif($action->nama =='Hapus')
                                                <a id="{{$data_stok->id}}" slug='{{$action->slug}}' nama='{{$data_stok->nama_user}}' page="Data Stok" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
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
