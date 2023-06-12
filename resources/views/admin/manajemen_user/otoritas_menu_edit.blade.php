@extends('admin.layout._layout')

@section('title', 'Otoritas Menu')

@section('content')
@include('admin.layout._alert_hapus')
@include('admin.layout._alert_edit')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Otoritas Menu</h4>
                {{ Form::open(['url' => '/admin/manajemen-user/otoritas-menu/update/'.$data_edit[0]['id'],'class'=>'border p-3','method' => 'post']) }}
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="jabatan_id">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan_id" id="jabatan_id" class="form-control custom-select">
                                <option value="{{$data_edit[0]['id']}}">{{$data_edit[0]['nama_jabatan']}}</option>
                            </select>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>Menu Utama </th>
                            <th>Sub Menu 1</th>
                            <th>Sub Menu 2</th>
                        </thead>
                        <tbody>
                            @php($i_menu = 0)
                            @foreach ($data_menu as $menu)
                            @php($i_menu = $i_menu + 1)
                                <tr>
                                @if (($menu['nama'] == "Laporan" || $menu['nama'] == "Trash" || $menu['nama'] == "Pengingat"||$menu['nama'] == "Dashboard"||$menu['nama'] == "Manage Running Text") )
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input @if(in_array($menu['id'], $data_menu_edit)) checked @endif type="checkbox" class="custom-control-input menu-{{$i_menu}}" id="{{$menu['id']}}" name="menu_utama[]" value="{{$menu['id']}}">
                                        <label class="custom-control-label" for="{{$menu['id']}}">{{$menu['nama']}}</label>
                                    </div>
                                </td><td></td><td></td></tr>
                                @else
                                <td>
                                     {{-- <label for="{{$menu['nama']}}">{{ $menu['nama'] }}</label><input type="checkbox" name="" id="{{$menu['nama']}}"> --}}
                                     <div class="custom-control custom-checkbox">
                                        <input @if(in_array($menu['id'], $data_menu_edit)) checked @endif type="checkbox" class="custom-control-input menu-{{$i_menu}}" id="{{$menu['id']}}" name="menu_utama[]" value="{{$menu['id']}}">
                                        <label class="custom-control-label" for="{{$menu['id']}}">{{$menu['nama']}}</label>
                                    </div>
                                </td>
                                @php ($setara = 1)

                                <td>
                                    {{-- <ul > --}}
                                    @php($i_sub_menu1 = 0)
                                    @foreach ($sub_menu1s as $item)
                                    @php($i_sub_menu1 = $i_sub_menu1 + 1)
                                    @php ($setara2 = 1)
                                        @if ($setara==1)
                                            @if ($item['id_menu_utama']==$menu['id'])
                                            @php($i_sub_menu1 = 1)
                                                <div class="custom-control custom-checkbox">
                                                    <input @if(in_array($item['id'], $data_sub_menu1_edit)) checked @elseif(in_array($menu['id'], $data_menu_edit))  @else disabled @endif type="checkbox" class="custom-control-input sub-{{$i_menu}} menu-{{$i_menu}}-{{$i_sub_menu1}}" id="{{$item['id']}}" name="sub_menu1[]" value="{{$item['id']}}">
                                                    <label class="custom-control-label" for="{{$item['id']}}">{{$item['nama']}}</label>
                                                </div>
                                            {{-- {{$item['nama']}}  --}}
                                            </td>
                                                {{-- <ul> --}}
                                                    @php($i_sub_menu2 = 0)
                                                    @foreach ($sub_menu2s as $item2)
                                                    @php($i_sub_menu2 = $i_sub_menu2 + 1)
                                                    @if ($setara2 == 1)
                                                        @php($i_sub_menu2 = 1)
                                                        @if ($item2['id_sub_menu1']==$item['id'])
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input @if(in_array($item2['id'], $data_sub_menu2_edit)) checked @elseif(in_array($item['id'], $data_sub_menu1_edit))  @else disabled @endif type="checkbox" class="custom-control-input sub2-{{$i_menu}}-{{$i_sub_menu1}} menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}" id="{{$item2['id']}}" name="sub_menu2[]" value="{{$item2['id']}}">
                                                                    <label class="custom-control-label" for="{{$item2['id']}}">{{$item2['nama']}}</label>
                                                                </div>
                                                            </td></tr>
                                                            @php ($setara2 = 0)
                                                        @endif
                                                    @else
                                                    {{-- asdasdasdasd --}}
                                                        @if ($item2['id_sub_menu1']==$item['id'])
                                                        </tr><tr><td></td><td></td><td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input @if(in_array($item2['id'], $data_sub_menu2_edit)) checked @elseif(in_array($item['id'], $data_sub_menu1_edit))  @else disabled @endif type="checkbox" class="custom-control-input sub2-{{$i_menu}}-{{$i_sub_menu1}} menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}" id="{{$item2['id']}}" name="sub_menu2[]" value="{{$item2['id']}}">
                                                                <label class="custom-control-label" for="{{$item2['id']}}">{{$item2['nama']}}</label>
                                                            </div>
                                                        </td></tr>
                                                        @endif
                                                    @endif
                                                    @endforeach
                                                {{-- </ul> --}}
                                                @php ($setara = 0)
                                            @endif
                                        {{-- @continue --}}
                                        @else
                                            @if ($item['id_menu_utama']==$menu['id'])
                                            </td></tr><tr><td></td><td>
                                                <div class="custom-control custom-checkbox">
                                                    <input @if(in_array($item['id'], $data_sub_menu1_edit)) checked @elseif(in_array($menu['id'], $data_menu_edit))  @else disabled @endif type="checkbox" class="custom-control-input sub-{{$i_menu}} menu-{{$i_menu}}-{{$i_sub_menu1}}" id="{{$item['id']}}" name="sub_menu1[]" value="{{$item['id']}}">
                                                    <label class="custom-control-label" for="{{$item['id']}}">{{$item['nama']}}</label>
                                                </div>
                                            </td>
                                                @php($i_sub_menu2 = 0)
                                                @foreach ($sub_menu2s as $item2)
                                                @php($i_sub_menu2 = $i_sub_menu2 + 1)
                                                @if ($setara2 == 1)
                                                    @php($i_sub_menu2 = 1)
                                                    @if ($item2['id_sub_menu1']==$item['id'])
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input @if(in_array($item2['id'], $data_sub_menu2_edit)) checked @elseif(in_array($item['id'], $data_sub_menu1_edit))  @else disabled @endif type="checkbox" class="custom-control-input sub2-{{$i_menu}}-{{$i_sub_menu1}} menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}" id="{{$item2['id']}}" name="sub_menu2[]" value="{{$item2['id']}}">
                                                            <label class="custom-control-label" for="{{$item2['id']}}">{{$item2['nama']}}</label>
                                                        </div>
                                                    </td></tr>
                                                        @php ($setara2 = 0)
                                                    @endif
                                                @else
                                                {{-- asdasdasdasd --}}
                                                    @if ($item2['id_sub_menu1']==$item['id'])
                                                    </tr></tr><tr><td></td><td></td><td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input @if(in_array($item2['id'], $data_sub_menu2_edit)) checked @elseif(in_array($item['id'], $data_sub_menu1_edit))  @else disabled @endif type="checkbox" class="custom-control-input sub2-{{$i_menu}}-{{$i_sub_menu1}} menu-{{$i_menu}}-{{$i_sub_menu1}}-{{$i_sub_menu2}}" id="{{$item2['id']}}" name="sub_menu2[]"value="{{$item2['id']}}">
                                                            <label class="custom-control-label" for="{{$item2['id']}}">{{$item2['nama']}}</label>
                                                        </div>
                                                    </td></tr>
                                                    @endif
                                                @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                    {{-- </ul> --}}
                                {{-- </td> --}}
                                @endif
                                {{-- </tr> --}}
                            @endforeach
                        </tbody>
                    </table>

                    {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
                    <a href="{{route('otoritas-menu')}}" class="btn btn-link" role="button">Batal</a>
                {{-- </form> --}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
