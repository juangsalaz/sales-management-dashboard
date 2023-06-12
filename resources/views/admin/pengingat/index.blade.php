@extends('admin.layout._layout')

@section('title', 'Modul Pengingat')

@section('content')
@include('admin.layout._alert_hapus')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('pengingat-add')}}" class="btn btn-primary" role="button">TAMBAH</a>
                <a href="" class="btn btn-link" role="button">Export to Excel</a>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Pengingat</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Keterangan</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i=0;
                            @endphp
                            @foreach($pengingats_now as $pengingat_now)
                            @php
                                $i=$i+1;
                            @endphp
                                <tr class="table-success">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pengingat_now->pengingat }}</td>
                                    <td>{{ date("d M Y", strtotime($pengingat_now->tanggal)) }}</td>
                                    <td>{{ $pengingat_now->jam }}</td>
                                    <td>{{ $pengingat_now->keterangan }}</td>
                                    <td>
                                        <a href="{{route('pengingat-edit', $pengingat_now->id)}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                            <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                        </a>

                                        <a id="{{$pengingat_now->id}}" slug="pengingat/delete" nama='{{$pengingat_now->pengingat}}' page="pengingat" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
                                            <img style="width:16px;vertical-align:middle;" src="{{ URL::asset('images/icon/deletew.png') }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach($pengingats as $pengingat)
                            @php
                                $i=$i+1;
                            @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $pengingat->pengingat }}</td>
                                    <td>{{ date("d M Y", strtotime($pengingat->tanggal)) }}</td>
                                    <td>{{ $pengingat->jam }}</td>
                                    <td>{{ $pengingat->keterangan }}</td>
                                    <td>
                                        <a href="{{route('pengingat-edit', $pengingat->id)}}" style="background-color:#FFD601;" class="btn rounded p-1">
                                            <img style="width:16px;" src="{{ URL::asset('images/icon/editw.png') }}">
                                        </a>

                                        <a id="{{$pengingat->id}}" slug="pengingat/delete" nama='{{$pengingat->pengingat}}' page="pengingat" href="javascript:;" style="background-color:#FF7575;" class="delete_btn btn rounded p-1">
                                            <img style="width:16px;vertical-align:middle;" src="{{ URL::asset('images/icon/deletew.png') }}">
                                        </a>
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
