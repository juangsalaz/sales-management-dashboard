<?php

namespace App\Exports;

use App\data_distributor;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use DB;

class DataDistributorExport implements FromView, WithEvents
{
    public function view(): View{
        $data_distributors = DB::table('data_distributors')
                                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'data_distributors.users')
                                        ->select('data_distributors.id', 'daftar_users.nama_user')
                                        ->whereNull('data_distributors.deleted_at')
                                        ->get();

        $data_distributor_area = DB::table('distributor_areas')
                                    ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'distributor_areas.coverage_area_id')
                                    ->select('distributor_areas.data_distributor_id', 'coverage_areas.nama_coverage_area')
                                    ->get();

        $data_distributor_cabang = DB::table('distributor_cabangs')
                                    ->leftJoin('cabangs', 'cabangs.id', '=', 'distributor_cabangs.cabang_id')
                                    ->select('distributor_cabangs.data_distributor_id', 'cabangs.nama_cabang')
                                    ->get();

        $data_distributor_produk = DB::table('distributor_produks')
                                    ->leftJoin('produks', 'produks.id', '=', 'distributor_produks.produk_id')
                                    ->select('distributor_produks.data_distributor_id', 'produks.nama as nama_produk')
                                    ->get();

        $data_distributor_distributor = DB::table('distributor_distributors')
                                    ->leftJoin('distributors', 'distributors.id', '=', 'distributor_distributors.distributor_id')
                                    ->select('distributor_distributors.data_distributor_id', 'distributors.nama_distributor')
                                    ->get();
        return view('admin.manajemen_distributor.data_distributor.data_distributor_table', [
            'data_distributors'=>$data_distributors,
            'data_distributor_area'=>$data_distributor_area,
            'data_distributor_cabang'=>$data_distributor_cabang,
            'data_distributor_produk'=>$data_distributor_produk,
            'data_distributor_distributor'=>$data_distributor_distributor,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(60);
                $event->sheet->getDelegate()->getStyle('A1:F'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A1:E'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:E'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
