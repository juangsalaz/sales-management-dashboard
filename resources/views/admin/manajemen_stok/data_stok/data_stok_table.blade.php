<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Produk</th>
            <th>User</th>
            <th>Area</th>
            <th>Distributor</th>
            <th>Cabang</th>
            <th>Kuantiti</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_stoks as $data_stok)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data_stok->nama_produk }}</td>
                <td>{{ $data_stok->nama_user }}</td>
                <td>{{ $data_stok->coverage_area }}</td>
                <td>{{ $data_stok->distributor }}</td>
                <td>{{ $data_stok->cabang }}</td>
                <td>{{ $data_stok->kuantiti }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
