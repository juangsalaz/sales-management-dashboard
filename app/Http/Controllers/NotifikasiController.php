<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\notifikasi;
use DB;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    protected $daftar_menu;
    protected $daftar_sub_menu;
    protected $daftar_sub_menu2;
    protected $jabatan;
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
                        ->where('sub_menu1s.nama', 'Daftar Bagian')
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
        $notifikasi = new \App\notifikasi();
        $data_notifikasi = $notifikasi->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                            ->leftJoin('daftar_users','daftar_users.id','=','id_user')
                            ->orderBy('notifikasis.created_at','desc')->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'notifikasis'=>$data_notifikasi,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();

        $dm = DB::table('jabatans')
                    ->select('jabatans.*')
                    ->where('nama_jabatan', 'DM')->get();
        
        $id_dm = $dm[0]->id;

        $data_dm = DB::table('daftar_users')
                        ->select('daftar_users.*')
                        ->where('jabatan', $id_dm)->get();
        $data['data_dm'] = $data_dm;

        return view('admin.notifikasi.index',$data);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'notif'  => 'required',
            'status_update' => 'required'
        ]);

        for($i=0; $i < count($request->notif); $i++) {
            $is_estimasi = DB::table('estimasis')
                            ->select('estimasis.*')
                            ->where('estimasis.id', $request->notif[$i])->get();

            if ($is_estimasi->isNotEmpty()) {
                if ($request->status_update == 1) {
                    DB::table('estimasis')
                    ->where('estimasis.id', $request->notif[$i])
                    ->update(array('status' => 'Diterima'));
                } else {
                    DB::table('estimasis')
                    ->where('estimasis.id', $request->notif[$i])
                    ->update(array('status' => 'Ditolak'));
                }
                DB::table('notifikasis')
                    ->where('notifikasis.id_estimasi', $request->notif[$i])
                    ->update(array('read' => 1));
            } else {
                if ($request->status_update == 1) {
                    $data_real_sales = DB::table('real_sales')
                                    ->select('real_sales.*')
                                    ->where('real_sales.id', $request->notif[$i])->get();

                    $id_produk = $data_real_sales[0]->produk;
                    $id_cabang = $data_real_sales[0]->cabang_id;
                    $jumlah_barang_keluar = $data_real_sales[0]->kuantiti;
                    $user = $data_real_sales[0]->nama_user;
                    $produk = $data_real_sales[0]->produk;
                    $cabang_id = $data_real_sales[0]->cabang_id;

                    $eksisting_produk_data = DB::table('produk_cabangs')
                                                    ->select('produk_cabangs.*')
                                                    ->where('produk_cabangs.produk_id', $id_produk)
                                                    ->where('produk_cabangs.cabang_id', $id_cabang)->get();

                    $eksisting_stok = $eksisting_produk_data[0]->jumlah;

                    if ($eksisting_stok > $jumlah_barang_keluar) {
                        $update_stok = $eksisting_stok-$jumlah_barang_keluar;

                        DB::table('produk_cabangs')
                            ->where('produk_cabangs.produk_id', $id_produk)
                            ->where('produk_cabangs.cabang_id', $id_cabang)
                            ->update(array('jumlah' => $update_stok));

                        DB::table('data_stoks')->insert(
                            array('user' => $user, 'kuantiti' => $jumlah_barang_keluar, 'produk' => $produk, 'cabang_id' => $cabang_id, 'type'=>'Real Sales', 'created_at'=>$this->now(), 'updated_at'=>$this->now())
                        );
                    }

                    DB::table('real_sales')
                    ->where('real_sales.id', $request->notif[$i])
                    ->update(array('status' => 'Diterima'));
                } else {
                    DB::table('real_sales')
                    ->where('real_sales.id', $request->notif[$i])
                    ->update(array('status' => 'Ditolak'));
                }
                DB::table('notifikasis')
                    ->where('notifikasis.id_real_sales', $request->notif[$i])
                    ->update(array('read' => 1));
            }
        }

        return redirect()->to(route('notifikasi'))->with('success','Berhasil Mengubah Data');
    }

    public function filter(Request $request)
    {

        $this->fill();
        $data = $this->getMenuAll();
        
        $dm = DB::table('jabatans')
                    ->select('jabatans.*')
                    ->where('nama_jabatan', 'DM')->get();
        
        $id_dm = $dm[0]->id;

        $data_dm = DB::table('daftar_users')
                        ->select('daftar_users.*')
                        ->where('jabatan', $id_dm)->get();
        $data['data_dm'] = $data_dm;

        $start="";
        $end="";

        $start = date('Y-m-d H:m:i', strtotime($request->start));
        $end = date('Y-m-d H:m:i', strtotime($request->end));
        
        if ($request->user != 0) {
            if ($request->start != null or $request->end != null) {
                if ($request->jenis == 0) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->where('notifikasis.id_user', $request->user)
                                    ->whereBetween('notifikasis.created_at', [$start, $end])
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 1) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->where('notifikasis.id_user', $request->user)
                                    ->whereNotNull('notifikasis.id_estimasi')
                                    ->whereBetween('notifikasis.created_at', [$start, $end])
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 2) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->where('notifikasis.id_user', $request->user)
                                    ->whereNotNull('notifikasis.id_real_sales')
                                    ->whereBetween('notifikasis.created_at', [$start, $end])
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                }
            } else {
                if ($request->jenis == 0) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->where('notifikasis.id_user', $request->user)
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 1) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->where('notifikasis.id_user', $request->user)
                                    ->whereNotNull('notifikasis.id_estimasi')
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 2) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->where('notifikasis.id_user', $request->user)
                                    ->whereNotNull('notifikasis.id_real_sales')
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                }
            }
        } else {
            if ($request->start != null or $request->end != null) {
                if ($request->jenis == 0) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->whereBetween('notifikasis.created_at', [$start, $end])
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 1) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->whereNotNull('notifikasis.id_estimasi')
                                    ->whereBetween('notifikasis.created_at', [$start, $end])
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 2) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->whereNotNull('notifikasis.id_real_sales')
                                    ->whereBetween('notifikasis.created_at', [$start, $end])
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                }
            } else {
                if ($request->jenis == 0) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 1) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->whereNotNull('notifikasis.id_estimasi')
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                } else if ($request->jenis == 2) {
                    $data_notifikasi = DB::table('notifikasis')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'notifikasis.id_user')
                                    ->whereNotNull('notifikasis.id_real_sales')
                                    ->select('daftar_users.nama_user','notifikasis.id_estimasi','notifikasis.id_real_sales', 'notifikasis.read', 'notifikasis.created_at')
                                    ->orderBy('notifikasis.created_at','desc')->get();
                }
            }
        }

        $data['notifikasis'] = $data_notifikasi;
        return view('admin.notifikasi.index',$data);
    }

}
