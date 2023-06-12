<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Distributor</th>
            <th>User</th>
            <th>Area</th>
            <th>Cabang</th>
            <th>Produk</th>
            <th>Target</th>
        </tr>
    </thead>
    <tbody>
        @foreach($target_distributor as $target_dist)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $target_dist->bulan }}</td>
                <td>{{ $target_dist->tahun }}</td>
                <td>{{ $target_dist->nama_distributor }}</td>
                <td>{{ $target_dist->nama_user }}</td>
                <td>{{ $target_dist->coverage_area }}</td>
                <td>{{ $target_dist->cabang }}</td>
                <td>{{ $target_dist->produk }}</td>
                <td>{{ $target_dist->target }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
