<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class RealSalesExport implements FromView, WithEvents
{
    public function __construct($jabatan_user, $dm=null, $bulan=null, $tahun=null)
    {
        $this->jabatan_user = $jabatan_user;
        $this->dm = $dm;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }
    public function view(): View{
        $query = DB::table('real_sales')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                        ->leftJoin('outlets', 'outlets.id', '=', 'real_sales.outlet')
                        ->leftJoin('produks', 'produks.id', '=', 'real_sales.produk')
                        ->select('real_sales.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                        ->whereNull('real_sales.deleted_at')
                        ->orderBy('real_sales.created_at','desc');
        if($this->dm != null){
            $query->where('real_sales.nama_user',$this->dm);
        }
        if ($this->bulan != null and $this->tahun != null) {
            $awal = date('Y-m-d', strtotime($this->tahun.'-'.$this->bulan.'-1'));
            $akhir = date('Y-m-t', strtotime($this->tahun.'-'.$this->bulan.'-1'));
            $query->whereBetween('real_sales.bulan', [$awal, $akhir]);
        }
        $real_sales = $query->get();

        $data = array('real_sales'=>$real_sales);

        return view('admin.manajemen_marketing.real_sales.real_sales_table', $data);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                for($i='A';$i<'N';$i++){
                    $event->sheet->getDelegate()->getColumnDimension($i)->setAutoSize(true);
                }
            },
        ];
    }
}
