<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class EstimasiExport implements FromView, WithEvents {
    public function __construct($jabatan_user, $dm=null, $bulan=null, $tahun=null)
    {
        $this->jabatan_user = $jabatan_user;
        $this->dm = $dm;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }
    public function view(): View{
        $query = DB::table('estimasis')
                                ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                                ->leftJoin('outlets', 'outlets.id', '=', 'estimasis.outlet')
                                ->leftJoin('produks', 'produks.id', '=', 'estimasis.produk')
                                ->select('estimasis.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                                ->whereNull('estimasis.deleted_at')
                                ->orderBy('estimasis.created_at','desc');
        if($this->dm != null){
            $query->where('estimasis.nama_user',$this->dm);
        }
        if($this->bulan != null and $this->tahun != null){
            $awal = date('Y-m-d', strtotime($this->tahun.'-'.$this->bulan.'-1'));
            $akhir = date('Y-m-t', strtotime($this->tahun.'-'.$this->bulan.'-1'));
            $query->whereBetween('estimasis.bulan', [$awal, $akhir]);
        }

        $estimasis = $query->get();

        $data = array('estimasis'=>$estimasis);

        return view('admin.manajemen_marketing.estimasi.estimasi_table', $data);
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
