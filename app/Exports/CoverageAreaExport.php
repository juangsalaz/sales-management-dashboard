<?php

namespace App\Exports;

use App\coverage_area;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class CoverageAreaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return coverage_area::all();
    }
    public function map($row): array
    {
        return [
            $row->nama_coverage_area,
            $row->keterangan,
            $row->created_at,
            $row->updated_at,
            $row->deleted_at,
        ];
    }
    public function headings(): array
    {
        return [
            'Nama Coverage Area',
            'Keterangan',
            'Dibuat tanggal',
            'Diubah tanggal',
            'Dihapus tanggal',
        ];
    }
}
