<?php

namespace App\Exports;

use App\daftar_user;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithMapping;

class DaftarUserExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStrictNullComparison, WithMapping
// class DaftarUserExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return daftar_user::all();
    }
    public function map($user): array
    {
        $bagians = new \App\bagian;
        $bagian = $bagians->where('id','=',$user->bagian)->get();
        $jabatans = new \App\jabatan;
        $jabatan = $jabatans->where('id','=',$user->jabatan)->get();
        $statuses = new \App\Status;
        $status = $statuses->where('id','=',$user->status)->get();
        return [
            $user->nama_user,
            $user->username,
            $bagian[0]['nama_bagian'],
            $jabatan[0]['nama_jabatan'],
            $status[0]['status'],
            $user->created_at,
            $user->updated_at,
            $user->deleted_at,
        ];
    }
    public function headings(): array
    {
        return [
            'Nama User',
            'Username',
            'Bagian',
            'Jabatan',
            'Status',
            'Dibuat tanggal',
            'Diubah tanggal',
            'Dihapus tanggal',
        ];
    }
}
