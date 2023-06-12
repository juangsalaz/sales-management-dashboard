<?php

namespace App\Exports;

use App\target_distributor;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use DB;

class TargetDistributorExport implements FromView, WithEvents
{
    public function view(): View{
        $target_distributor = DB::table('target_distributors')
                                ->leftJoin('daftar_users', 'daftar_users.id', '=', 'target_distributors.user')
                                ->leftJoin('distributors', 'distributors.id', '=', 'target_distributors.distributor')
                                ->select('target_distributors.*', 'daftar_users.nama_user', 'distributors.nama_distributor')
                                ->whereNull('target_distributors.deleted_at')
                                ->get();
        $i=0;
        foreach($target_distributor as $target_dist) {
            $coverage_areas = DB::table('data_distributors')
                                ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
                                ->leftJoin('distributor_areas', 'data_distributors.id', '=', 'distributor_areas.data_distributor_id')
                                ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'distributor_areas.coverage_area_id')
                                ->select('coverage_areas.nama_coverage_area')->distinct()
                                ->where('distributor_distributors.distributor_id', $target_dist->distributor)
                                ->get();

            $coverage_area = '';
            foreach($coverage_areas as $area) {
                if ($coverage_area != '') {
                    $coverage_area = $coverage_area.', '.$area->nama_coverage_area;
                } else {
                    $coverage_area = $coverage_area.''.$area->nama_coverage_area;
                }

            }

            $target_distributor[$i]->coverage_area = $coverage_area;

            $cabangs = DB::table('data_distributors')
                        ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
                        ->leftJoin('distributor_cabangs', 'data_distributors.id', '=', 'distributor_cabangs.data_distributor_id')
                        ->leftJoin('cabangs', 'cabangs.id', '=', 'distributor_cabangs.cabang_id')
                        ->select('cabangs.nama_cabang')->distinct()
                        ->where('distributor_distributors.distributor_id', $target_dist->distributor)
                        ->whereNull('data_distributors.deleted_at')
                        ->get();

            $cabang = '';
            foreach ($cabangs as $row) {
                if ($cabang != '') {
                    $cabang = $cabang.', '.$row->nama_cabang;
                } else {
                    $cabang = $cabang.''.$row->nama_cabang;
                }
            }

            $target_distributor[$i]->cabang = $cabang;

            $produks = DB::table('data_distributors')
                        ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
                        ->leftJoin('distributor_produks', 'data_distributors.id', '=', 'distributor_produks.data_distributor_id')
                        ->leftJoin('produks', 'produks.id', '=', 'distributor_produks.produk_id')
                        ->select('produks.nama as nama_produk')->distinct()
                        ->where('distributor_distributors.distributor_id', $target_dist->distributor)
                        ->whereNull('data_distributors.deleted_at')
                        ->get();

            $produk = '';
            foreach ($produks as $row) {
                if ($produk != '') {
                    $produk = $produk.', '.$row->nama_produk;
                } else {
                    $produk = $produk.''.$row->nama_produk;
                }
            }

            $target_distributor[$i]->produk = $produk;

            $i++;
        }
        return view('admin.manajemen_marketing.terget_distributor.target_distributor_table', [
            'target_distributor'=>$target_distributor,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getStyle('A1:H'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A1:E'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F1:H'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                $event->sheet->getDelegate()->getStyle('A1:E'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('I2:I'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('I2:I'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
