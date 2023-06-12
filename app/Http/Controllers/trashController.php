<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class trashController extends Controller
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

        $estimasis = DB::table('estimasis')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                        ->leftJoin('outlets', 'outlets.id', '=', 'estimasis.outlet')
                        ->leftJoin('produks', 'produks.id', '=', 'estimasis.produk')
                        ->select('estimasis.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                        ->whereNotNull('estimasis.deleted_at')
                        ->get();

        $real_sales = DB::table('real_sales')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                        ->leftJoin('outlets', 'outlets.id', '=', 'real_sales.outlet')
                        ->leftJoin('produks', 'produks.id', '=', 'real_sales.produk')
                        ->select('real_sales.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                        ->whereNotNull('real_sales.deleted_at')
                        ->get();

        $produks = DB::table('produks')->select('*')->whereNotNull('deleted_at')->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'estimasis'=>$estimasis, 'real_sales'=>$real_sales, 'produks'=>$produks, 'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        $this->$now = Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.trash.index',$data);
    }

    public function restore(Request $request)
    {
        $request->validate([
                    'estimasis' => 'required',
                ]);

        if ($request->action != 'hapus') {
            foreach($request->estimasis as $estimasi) {
                DB::table('estimasis')->select('*')->where('id', $estimasi)->update(['deleted_at' => null]);
            }
        } else {
            foreach($request->estimasis as $estimasi) {
                DB::table('estimasis')->select('*')->where('id', $estimasi)->delete();
            }
        }

        return redirect()->to(route('trash'))->with('success','Berhasil Menambahkan Data');
    }

    public function restoreRealSales(Request $request)
    {
        $request->validate([
            'real_sales' => 'required',
        ]);

        if ($request->action != 'hapus') {
            foreach($request->real_sales as $sales) {
                DB::table('real_sales')->select('*')->where('id', $sales)->update(['deleted_at' => null]);
            }
        } else {
            foreach($request->real_sales as $sales) {
                DB::table('real_sales')->select('*')->where('id', $sales)->delete();
            }
        }

        return redirect()->to(route('trash'))->with('success','Berhasil Menambahkan Data');
    }

    public function restoreProduks(Request $request)
    {
        $request->validate([
            'produks' => 'required',
        ]);

        if ($request->action != 'hapus') {
            foreach($request->produks as $sales) {
                DB::table('produks')->select('*')->where('id', $sales)->update(['deleted_at' => null]);
            }
        } else {
            foreach($request->produks as $sales) {
                DB::table('produks')->select('*')->where('id', $sales)->delete();
            }
        }

        return redirect()->to(route('trash'))->with('success','Berhasil Menambahkan Data');
    }
}
