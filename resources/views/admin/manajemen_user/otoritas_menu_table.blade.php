<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Jabatan</th>
            <th>Menu</th>
        </tr>
    </thead>
    <tbody>
        @isset($jabatan_otoritas)

        @foreach ($jabatan_otoritas as $jabatan)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                {{ $jabatan['nama'] }}
            </td>
            <td>
                @foreach ($data_menu_otoritas as $menu)
                    @if ($menu['nama_jabatan']==$jabatan['nama'])
                        {{ $menu['nama'] }},
                    @endif
                @endforeach
                @foreach ($data_sub_menu_otoritas as $sub)
                    @if ($sub['nama_jabatan']==$jabatan['nama'])
                        {{ $sub['nama'] }},
                    @endif
                @endforeach
            </td>
        </tr>
        @endforeach
        @endisset
    </tbody>
</table>
