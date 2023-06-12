<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <div class="user-profile">
            <div class="user-pro-body">
                <div style="text-align:center;">
                    <h4>Sales Management</h4>
                </div>
                <div class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$pengguna}}</a>
                    <div class="dropdown-menu">
                        <a href="/admin/settings/edit-password" class="dropdown-item"> Setting User</a>
                        <!-- text-->
                        <div class="dropdown-divider"></div>
                        <!-- text-->
                        <a href="{{ route('logout') }}" class="dropdown-item"> Logout</a>
                        <!-- text-->
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                @foreach ($data_menu as $menu)
                @if ($menu['nama'] == "Laporan" || $menu['nama'] == "Trash" || $menu['nama'] == "Pengingat"||$menu['nama'] == "Dashboard"||$menu['nama'] == "Manage Running Text")
            <li> <a class="waves-effect waves-dark" href="{{ $menu['slug'] }}" aria-expanded="false"><img width="16px" src="{{URL::asset($menu['slug_icon'])}}"><span class="hide-menu">&nbsp;&nbsp;&nbsp;{{ $menu['nama'] }}</span></a></li>
                @else
                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><img width="16px" src="{{URL::asset($menu['slug_icon'])}}"><span class="hide-menu">&nbsp;&nbsp;&nbsp;{{ $menu['nama'] }}</span></a>
                    <ul aria-expanded="false" class="collapse">
                    @foreach ($data_sub_menu as $item)
                        @if ($item->id_menu==$menu->id)
                            <li><a href="{{$item->slug}}">{{$item->nama}} </a></li>
                        @endif
                    @endforeach
                    </ul>
                </li>
                @endif
                @endforeach
                {{-- <li> <a class="waves-effect waves-dark" href="{{route('dashboard-backup')}}"> <img width="16px" src="{{URL::asset('images/icon/download.png')}}"><span class="hide-menu">&nbsp;&nbsp;&nbsp;Backup Database</span></a></li> --}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
