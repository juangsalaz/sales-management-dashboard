<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\produk;
use App\data_stok;
use DB;
use Carbon\Carbon;
use App\Exports\DataStokExport;
use Maatwebsite\Excel\Facades\Excel;

class dataStokController extends Controller
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
                        ->where('sub_menu1s.nama', 'Data Stok')
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

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'data_stoks'=>$data_stoks,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);

        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_stok.data_stok.index',$data);
    }
    public function add(){
        $this->fill();
        $data = $this->getMenuAll();

        $produks = produk::all();
        $data['produks'] = $produks;

        return view('admin.manajemen_stok.data_stok.add',$data);
    }
    public function store(Request $request){

        $request->validate([
            'produk'  => 'required',
            'kuantiti_produk'   => 'required'
        ]);

        $data_stok = new \App\data_stok();
        $data_stok->insert(['produk'=>$request->produk,'kuantiti'=>$request->kuantiti_produk, 'user'=>Auth::user()->id, 'created_at'=>date('Y-m-d'), 'updated_at'=>date('Y-m-d')]);

        return redirect()->to(route('data-stok'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();

        $produks = produk::all();
        $data['produks'] = $produks;

        $data_stok = new \App\data_stok();
        $data['stok'] = $data_stok->where('id',$id)->get();

        return view('admin.manajemen_stok.data_stok.edit', $data);
    }
    public function update(Request $request){
        $request->validate([
            'produk'  => 'required',
            'kuantiti_produk'   => 'required'
        ]);

        $data_stok = data_stok::find($request->id);
        $data_stok->produk = $request->produk;
        $data_stok->kuantiti = $request->kuantiti_produk;
        $data_stok->save();

        return redirect()->to(route('data-stok'))->with('success','Berhasil Menambahkan Data');
    }
    public function delete(Request $request){
        $data_stok = data_stok::find($request->id);
        $data_stok->delete();
        return redirect()->to(route('data-stok'))->with('success','Berhasil Menghapus Data');
    }
    public function export()
    {
        return Excel::download(new DataStokExport, 'data-stok.xlsx');
    }
}
