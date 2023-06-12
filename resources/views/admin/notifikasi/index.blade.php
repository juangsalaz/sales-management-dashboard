@extends('admin.layout._layout')

@section('title', 'Notifikasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{route('notif-filter')}}"> @csrf
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="nama_bagian">DM</label>
                            <select class="form-control" name="user">
                                <option value="0">Pilih DM</option>
                                @foreach($data_dm as $dm)
                                    <option value="{{$dm->id}}">{{$dm->nama_user}}</option>
                                @endforeach
                            </select>
                        </div>
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
                            <label for="nama_bagian">Jenis notifikasi</label>
                            <select class="form-control" name="jenis">
                                <option value="0">Semua</option>
                                <option value="1">Estimasi</option>
                                <option value="2">Real Sales</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary" style="margin-top: 28px;">Filter</button>
                        </div>
                    </div>
                </form>

                <hr>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="list-group">
                    @if (isset($notifikasis)&&!$notifikasis->isEmpty())
                        <form action="{{route('notif-update-status')}}" method="post"> @csrf
                            <div class="row" style="margin-bottom: 8px;">
                                <div class="col-md-1" style="max-width: 3%;">
                                    <input type="checkbox" class="form-control" style="margin-top: 15px;" id="check-notif-all">
                                </div>
                                <div class="col-md-11" style="padding-top:15px;">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <p style="font-weight: 500; margin: 8px 0 0 0;">Pilih Semua</p>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="status_update">
                                                    <option value="">Pilih Status Update</option>
                                                    <option value="1">Diterima</option>
                                                    <option value="0">Ditolak</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            @foreach ($notifikasis as $notifikasi)
                                <?php
                                    $created_at = $notifikasi->created_at;
                                    $date = date('j F Y', strtotime($created_at));
                                ?>
                                @if ($notifikasi->id_estimasi!=null)
                                    @if ($notifikasi->read==1)
                                        <div class="row">
                                            <div class="col-md-1" style="max-width: 3%;">
                                                <input type="checkbox" class="form-control notif-check" style="margin-top: 8px;" name="notif[]" value="{{ $notifikasi->id_estimasi }}" jenis="estimasi">
                                            </div>
                                            <div class="col-md-11">
                                                <a href="{{route('estimasi-status',['id'=>$notifikasi->id_estimasi])}}" class="list-group-item list-group-item-action"><span style="font-weight: 500;">{{$notifikasi->nama_user}}</span> telah menambahkan estimasi, pada <span style="font-weight: 500;">{{ $date }}</span></a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-1" style="max-width: 3%;">
                                                <input type="checkbox" class="form-control notif-check" style="margin-top: 8px;" name="notif[]" value="{{ $notifikasi->id_estimasi }}" jenis="estimasi">
                                            </div>
                                            <div class="col-md-11">
                                                <a style="background-color:#c9ffd5;" href="{{route('estimasi-status',['id'=>$notifikasi->id_estimasi])}}" class="list-group-item list-group-item-action"><span style="font-weight: 500;">{{$notifikasi->nama_user}}</span> telah menambahkan estimasi, pada <span style="font-weight: 500;">{{ $date }}</span></a>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($notifikasi->id_real_sales!=null)
                                    @if ($notifikasi->read==1)
                                        <div class="row">
                                            <div class="col-md-1" style="max-width: 3%;">
                                                <input type="checkbox" class="form-control notif-check" style="margin-top: 8px;" name="notif[]" value="{{ $notifikasi->id_real_sales }}" jenis="real_sales">
                                            </div>
                                            <div class="col-md-11">
                                            <a href="{{route('realsales-status',['id'=>$notifikasi->id_real_sales])}}" class="list-group-item list-group-item-action"><span style="font-weight: 500;">{{$notifikasi->nama_user}}</span> telah menambahkan real sales, pada <span style="font-weight: 500;">{{ $date }}</span></a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-1" style="max-width: 3%;">
                                                <input type="checkbox" class="form-control notif-check" style="margin-top: 8px;" name="notif[]" value="{{ $notifikasi->id_real_sales }}" jenis="real_sales">
                                            </div>
                                            <div class="col-md-11">
                                                <a style="background-color:#c9ffd5;" href="{{route('realsales-status',['id'=>$notifikasi->id_real_sales])}}" class="list-group-item list-group-item-action"><span style="font-weight: 500;">{{$notifikasi->nama_user}}</span> telah menambahkan real sales, pada <span style="font-weight: 500;">{{ $date }}</span></a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </form>
                    @else
                        <li class="list-group-item list-group-item-light">Tidak ada notifikasi</li>
                    @endif
                    {{-- <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                    <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                    <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
