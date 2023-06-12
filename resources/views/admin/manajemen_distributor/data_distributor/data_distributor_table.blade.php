<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Coverage Area</th>
            <th>Distributor</th>
            <th>Cabang</th>
            <th>Produk</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_distributors as $dist)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $dist->nama_user }}</td>
                <td>
                    @foreach ($data_distributor_area as $dist_area)
                        @if ($dist_area->data_distributor_id == $dist->id)
                            {{$dist_area->nama_coverage_area}}
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($data_distributor_distributor as $dist_distributor)
                        @if ($dist_distributor->data_distributor_id == $dist->id)
                            {{$dist_distributor->nama_distributor}}
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($data_distributor_cabang as $dist_cabang)
                        @if ($dist_cabang->data_distributor_id == $dist->id)
                            {{$dist_cabang->nama_cabang}}
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($data_distributor_produk as $dist_produk)
                        @if ($dist_produk->data_distributor_id == $dist->id)
                            {{$dist_produk->nama_produk}}
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
