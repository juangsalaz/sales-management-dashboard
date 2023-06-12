<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Produk</th>
            <th>Nama User</th>
            <th>Outlet</th>
            <th>Harga Produk</th>
            <th>Kuantiti</th>
            <th>Value Gross</th>
            <th>Diskon</th>
            <th>Diskon Value</th>
            <th>Value Nett</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($estimasis as $estimasi)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('F', strtotime($estimasi->bulan)) }}</td>
                <td>{{ $estimasi->tahun }}</td>
                <td>{{ $estimasi->nama_produk }}</td>
                <td>{{ $estimasi->nama_user }}</td>
                <td>{{ $estimasi->nama_outlet }}</td>
                <td>{{ $estimasi->harga_produk }}</td>
                <td>{{ $estimasi->kuantiti }}</td>
                <td>{{ $estimasi->value_gross }}</td>
                <td>{{ $estimasi->diskon }}</td>
                <td>{{ $estimasi->diskon_value }}</td>
                <td>{{ $estimasi->value_nett }}</td>
                <td>{{ $estimasi->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
