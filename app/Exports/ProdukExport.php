<?php

namespace App\Exports;

use App\produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProdukExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return produk::all();
    }
    public function map($row): array
    {
        return [
            $row->nama,
            $row->kode,
            $row->harga,
            $row->golongan,
            $row->created_at,
            $row->updated_at,
            $row->deleted_at,
        ];
    }
    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kode',
            'Harga',
            'Golongan',
            'Dibuat tanggal',
            'Diubah tanggal',
            'Dihapus tanggal',
        ];
    }
}
