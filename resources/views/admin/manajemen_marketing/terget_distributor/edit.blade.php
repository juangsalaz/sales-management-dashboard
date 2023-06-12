@extends('admin.layout._layout')

@section('title', 'Target Distributor')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')
@include('admin.manajemen_marketing.terget_distributor._alert_view')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Target Distributor</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ Form::open(['url' => '/admin/manajemen-marketing/target-distributor/update/'.$data_target_distributor[0]->id,'class'=>'border p-3 edit-form']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="bulan">Pilih Bulan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" name="bulan" placeholder="Pilih Bulan" value="{{ date('F', strtotime($data_target_distributor[0]->bulan)) }}" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Pilih Tahun <span class="text-danger">*</span></label>
                            <input type="text" class="form-control yearpicker" name="tahun" placeholder="Pilih Tahun" value="{{ $data_target_distributor[0]->tahun }}"/>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tahun">Pilih Distributor <span class="text-danger">*</span></label>
                            <select name="distributor" class="form-control">
                                <option value="">Pilih Distributor</option>
                                @foreach($distributors as $dist)
                                    @if($data_target_distributor[0]->distributor == $dist->id)
                                        <option value="{{$dist->id}}" selected>{{$dist->nama_distributor}}</option>
                                    @else
                                        <option value="{{$dist->id}}">{{$dist->nama_distributor}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nilai_target">Nilai Target <span class="text-danger">*</span></label>
                            <input type="text" class="form-control input-no-dec" name="nilai_target" placeholder="Masukkan Nilai" value="{{ $data_target_distributor[0]->target }}" />
                        </div>
                    </div>
                    {{ Form::button('Submit',['class'=>'btn btn-primary submit-edit']) }}
                    <a href="{{route('target-distributor')}}" class="btn btn-link" role="button">Batal</a>
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
