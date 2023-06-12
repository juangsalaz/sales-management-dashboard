<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use DB;

class DataStokExport implements FromView, WithEvents
{
    public function view(): View{
        $data_stoks = DB::table('data_stoks')
                        ->leftJoin('produks', 'produks.id', '=', 'data_stoks.produk')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'data_stoks.user')
                        ->select('data_stoks.*', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                        ->whereNull('data_stoks.deleted_at')->get();

        $i=0;
        foreach($data_stoks as $stok) {
            $coverage_areas = DB::table('data_distributors')
            ->leftJoin('distributor_produks', 'data_distributors.id', '=', 'distributor_produks.data_distributor_id')
            ->leftJoin('distributor_areas', 'data_distributors.id', '=', 'distributor_areas.data_distributor_id')
            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'distributor_areas.coverage_area_id')
            ->select('coverage_areas.nama_coverage_area')->distinct()
            ->where('distributor_produks.produk_id', $stok->produk)
            ->get();

            $coverage_area = '';
            foreach($coverage_areas as $area) {
                if ($coverage_area != '') {
                    $coverage_area = $coverage_area.', '.$area->nama_coverage_area;
                } else {
                    $coverage_area = $coverage_area.''.$area->nama_coverage_area;
                }

            }

            $data_stoks[$i]->coverage_area = $coverage_area;

            $distributors = DB::table('data_distributors')
            ->leftJoin('distributor_produks', 'data_distributors.id', '=', 'distributor_produks.data_distributor_id')
            ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
            ->leftJoin('distributors', 'distributors.id', '=', 'distributor_distributors.distributor_id')
            ->select('distributors.nama_distributor')->distinct()
            ->where('distributor_produks.produk_id', $stok->produk)
            ->get();

            $distributor = '';
            foreach($distributors as $dist) {
                if ($distributor != '') {
                    $distributor = $distributor.', '.$dist->nama_distributor;
                } else {
                    $distributor = $distributor.''.$dist->nama_distributor;
                }

            }

            $data_stoks[$i]->distributor = $distributor;

            $cabangs = DB::table('data_distributors')
            ->leftJoin('distributor_produks', 'data_distributors.id', '=', 'distributor_produks.data_distributor_id')
            ->leftJoin('distributor_cabangs', 'data_distributors.id', '=', 'distributor_cabangs.data_distributor_id')
            ->leftJoin('cabangs', 'cabangs.id', '=', 'distributor_cabangs.cabang_id')
            ->select('cabangs.nama_cabang')->distinct()
            ->where('distributor_produks.produk_id', $stok->produk)
            ->get();

            $data_cabang = '';
            foreach($cabangs as $cabang) {
                if ($data_cabang != '') {
                    $data_cabang = $data_cabang.', '.$cabang->nama_cabang;
                } else {
                    $data_cabang = $data_cabang.''.$cabang->nama_cabang;
                }

            }

            $data_stoks[$i]->cabang = $data_cabang;

            $i++;
        }
        return view('admin.manajemen_stok.data_stok.data_stok_table', [
            'data_stoks'=>$data_stoks,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(22);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(17);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getStyle('A1:G'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A2:G'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            },
        ];
    }
}
