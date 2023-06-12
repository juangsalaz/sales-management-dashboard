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
        @foreach($real_sales as $real_sales)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('F', strtotime($real_sales->bulan)) }}</td>
                <td>{{ $real_sales->tahun }}</td>
                <td>{{ $real_sales->nama_produk }}</td>
                <td>{{ $real_sales->nama_user }}</td>
                <td>{{ $real_sales->nama_outlet }}</td>
                <td>{{ $real_sales->harga_produk }}</td>
                <td>{{ $real_sales->kuantiti }}</td>
                <td>{{ $real_sales->value_gross }}</td>
                <td>{{ $real_sales->diskon }}</td>
                <td>{{ $real_sales->diskon_value }}</td>
                <td>{{ $real_sales->value_nett }}</td>
                <td>{{ $real_sales->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
