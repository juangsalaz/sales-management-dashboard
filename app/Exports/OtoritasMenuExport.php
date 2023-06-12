<?php

namespace App\Exports;
use App\menu_jabatan;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OtoritasMenuExport implements FromView, WithEvents
{
    /**
    * return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }
    public function view(): View
    {
        $jabatan = new \App\jabatan();
        $jabatans = $jabatan->all();
        $menu = new \App\menu_utama();
        $menus = $menu->all();
        $menu_jabatan = new \App\menu_jabatan();
        $menu_jabatans = $menu_jabatan->all();
        $jabatan_otoritas = $menu_jabatan->select('id_jabatan','jabatans.nama_jabatan as nama')->leftJoin('jabatans','jabatans.id','=','id_jabatan')->distinct()->get();
        $data_menu_otoritas = $menu_jabatan->select('id_jabatan', 'jabatans.nama_jabatan','id_menu','menu_utamas.nama as nama', 'menu_utamas.urutan')->leftJoin('jabatans','jabatans.id','=','id_jabatan')->leftJoin('menu_utamas','menu_utamas.id','=','id_menu')->whereRaw('id_jabatan = jabatans.id and menu_jabatans.id_sub_menu1 is null')->groupBy('id_jabatan','jabatans.nama_jabatan','id_menu','menu_utamas.nama','menu_utamas.urutan')->orderBy('menu_utamas.urutan','asc')->get();
        // dd($jabatan_otoritas);
        $data_sub_menu_otoritas = $menu_jabatan->select('id_jabatan', 'jabatans.nama_jabatan','id_sub_menu1','sub_menu1s.nama as nama')->leftJoin('jabatans','jabatans.id','=','id_jabatan')->leftJoin('sub_menu1s','sub_menu1s.id','=','id_sub_menu1')->whereRaw('id_jabatan = jabatans.id and menu_jabatans.id_sub_menu1 is not null')->groupBy('id_jabatan','jabatans.nama_jabatan','id_sub_menu1','sub_menu1s.nama')->get();
        // dd($data_sub_menu_otoritas);
        $sub_menu1 = new \App\sub_menu1();
        $sub_menu1s = $sub_menu1->all();
        $sub_menu2 = new \App\sub_menu2();
        $sub_menu2s = $sub_menu2->all();
        return view('admin.manajemen_user.otoritas_menu_table', [
            'menus'=>$menus,
            'sub_menu1s'=>$sub_menu1s,
            'sub_menu2s'=>$sub_menu2s,
            'jabatan_otoritas'=>$jabatan_otoritas,
            'data_sub_menu_otoritas'=>$data_sub_menu_otoritas,
            'data_menu_otoritas'=>$data_menu_otoritas,
            'jabatans'=>$jabatans
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(90);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
                $event->sheet->getDelegate()->getStyle('C1:C'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A1:B'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:B'.$event->sheet->getDelegate()->getHighestRow())->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
