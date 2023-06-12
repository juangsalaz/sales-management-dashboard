<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\cabang;
use DB;
use Carbon\Carbon;
use App\Exports\CabangExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class daftarCabangController extends Controller
{
    protected $daftar_menu;
    protected $daftar_sub_menu;
    protected $daftar_sub_menu2;
    protected $jabatan;
    protected $now;
    public function getJabatan(){
        $this->jabatan = Auth::user()->jabatan;
    }
    public function getMenuJabatan(){
        $menu = new \App\menu_jabatan();
        $this->daftar_menu = $menu->select('menu_utamas.nama', 'menu_utamas.id','menu_utamas.slug','menu_utamas.slug_icon','menu_utamas.urutan')->where('id_jabatan',$this->jabatan)->leftJoin('menu_utamas','menu_jabatans.id_menu','=','menu_utamas.id')->orderBy('menu_utamas.urutan','asc')->distinct()->get();
    }
    public function getSubMenu(){
        $menu = DB::table('menu_jabatans')
                    ->leftJoin('sub_menu1s', 'sub_menu1s.id', '=', 'menu_jabatans.id_sub_menu1')
                    ->select('sub_menu1s.id_menu_utama as id_menu', 'sub_menu1s.nama', 'sub_menu1s.slug', 'sub_menu1s.urutan')->distinct()
                    ->where('menu_jabatans.id_jabatan', $this->jabatan)
                    ->orderBy('sub_menu1s.urutan','asc')
                    ->get();
        $this->daftar_sub_menu = $menu;
    }
    public function getSubMenu2(){
        $submenu1 = DB::table('sub_menu1s')
                        ->select('sub_menu1s.id')
                        ->where('sub_menu1s.nama', 'Daftar Cabang')
                        ->get();

        $sub_menu2 = DB::table('menu_jabatans')
                    ->leftJoin('sub_menu2s', 'sub_menu2s.id', '=', 'menu_jabatans.id_sub_menu2')
                    ->select('sub_menu2s.nama', 'sub_menu2s.slug')->distinct()
                    ->where('menu_jabatans.id_jabatan', $this->jabatan)
                    ->where('menu_jabatans.id_sub_menu1', $submenu1[0]->id)
                    ->get();

        $this->daftar_sub_menu2 = $sub_menu2;
    }
    public function fill(){
        $this->getJabatan();
        $this->getMenuJabatan();
        $this->getSubMenu();
        $this->getSubMenu2();
    }
    public function getMenuAll(){$jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();
        $data_cabang = DB::table('cabangs')
                            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'cabangs.coverage_area_id')
                            ->select('cabangs.*', 'coverage_areas.nama_coverage_area')
                            ->whereNull('cabangs.deleted_at')
                            ->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();
        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'cabangs'=>$data_cabang,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        return Carbon::now();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_distributor.daftar_cabang.index',$data);
    }
    public function add(){
        $this->fill();
        $data = $this->getMenuAll();

        $coverage_areas = DB::table('coverage_areas')->select('*')->whereNull('deleted_at')->get();
        $data['coverage_areas'] = $coverage_areas;

        return view('admin.manajemen_distributor.daftar_cabang.add',$data);
    }
    public function store(Request $request){
        $request->validate([
            'coverage_area'  => 'required',
            'nama_cabang'  => 'required',
            'keterangan'   => 'required',
        ]);
        $cabang = new \App\cabang();
        $cabang->insert(['id' => Str::uuid(), 'nama_cabang'=>$request['nama_cabang'],'keterangan'=>$request['keterangan'], 'coverage_area_id'=>$request['coverage_area'],'created_at'=>$this->now(),'updated_at'=>$this->now()]);
        return redirect()->to(route('daftar-cabang'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();
        $cabang = new \App\cabang();
        $data['data_cabang'] = $cabang->where('id',$id)->get();

        $coverage_areas = DB::table('coverage_areas')->select('*')->whereNull('deleted_at')->get();
        $data['coverage_areas'] = $coverage_areas;

        return view('admin.manajemen_distributor.daftar_cabang.edit',$data);
    }
    public function update(Request $request){
        $request->validate([
            'nama_cabang'  => 'required',
            'keterangan'   => 'required',
        ]);
        $cabang = \App\cabang::find($request->id);
        $cabang->nama_cabang = $request->nama_cabang;
        $cabang->keterangan = $request->keterangan;
        $cabang->coverage_area_id = $request->coverage_area;
        $cabang->save();
        return redirect()->to(route('daftar-cabang'))->with('success','Berhasil Menambahkan Data');
    }
    public function delete(Request $request){
        $cabang = \App\cabang::find($request->id);
        $cabang->delete();
        return redirect()->to(route('daftar-cabang'))->with('success','Berhasil Menghapus Data');
    }
    public function export()
    {
        return Excel::download(new CabangExport, 'daftar-cabang.xlsx');
    }
}
