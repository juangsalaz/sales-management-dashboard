<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Produk</th>
            <th>User</th>
            <th>Area</th>
            <th>Distributor</th>
            <th>Cabang</th>
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
            </tr>
        @endforeach
    </tbody>
</table>
