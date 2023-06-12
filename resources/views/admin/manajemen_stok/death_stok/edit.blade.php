@extends('admin.layout._layout')

@section('title', 'Dead Stok')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')
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
                <h4 class="card-title">Form Dead Stok</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-stok/death-stok/update/'.$stok[0]->id,'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="produk">Pilih Produk <span class="text-danger">*</span></label>
                            <select name="produk" class="form-control js-example-basic-single">
                                <option value="">Pilih Produk</option>
                                @foreach($produks as $produk)
                                    @if($stok[0]->produk == $produk->id)
                                        <option value="{{$produk->id}}" selected>{{$produk->nama}}</option>
                                    @else
                                        <option value="{{$produk->id}}">{{$produk->nama}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('death-stok')}}" class="btn btn-link" role="button">Batal</a>
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
@endsection
