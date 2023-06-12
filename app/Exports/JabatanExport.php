<?php

namespace App\Exports;

use App\jabatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class JabatanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return jabatan::all();
    }
    public function map($row): array
    {
        return [
            $row->nama_jabatan,
            $row->keterangan,
            $row->created_at,
            $row->updated_at,
            $row->deleted_at,
        ];
    }
    public function headings(): array
    {
        return [
            'Nama Jabatan',
            'Keterangan',
            'Dibuat tanggal',
            'Diubah tanggal',
            'Dihapus tanggal',
        ];
    }
}
