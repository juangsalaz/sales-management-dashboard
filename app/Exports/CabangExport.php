<?php

namespace App\Exports;

use App\cabang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class CabangExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return cabang::all();
    }
    public function map($row): array
    {
        return [
            $row->nama_cabang,
            $row->keterangan,
            $row->created_at,
            $row->updated_at,
            $row->deleted_at,
        ];
    }
    public function headings(): array
    {
        return [
            'Nama Cabang',
            'Keterangan',
            'Dibuat tanggal',
            'Diubah tanggal',
            'Dihapus tanggal',
        ];
    }
}
