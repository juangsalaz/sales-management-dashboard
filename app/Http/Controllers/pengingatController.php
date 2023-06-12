<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\pengingat;
use DB;
use Carbon\Carbon;

class pengingatController extends Controller
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
        $user_id = Auth::user()->id;

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data_pengingat = $pengingat->where('user', $user_id)->where('tanggal','!=',date("Y-m-d"))->orWhere([['user','=', $user_id],['tanggal','=',date("Y-m-d")],['jam','<',date("H:i:s")]])->orderBy('tanggal','desc')->get();

        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'pengingats'=>$data_pengingat,'pengingats_now'=>$data_pengingat_now,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.pengingat.index',$data);
    }

    public function add(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.pengingat.add',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'  => 'required',
            'keterangan'   => 'required',
            'tanggal'   => 'required',
            'jam'   => 'required',
        ]);

        $tanggal = date("Y-m-d", strtotime($request->tanggal));
        $user_id = Auth::user()->id;

        // if ($request->setiap_hari != 'on') {
        //     $setiap_hari = 0;
        // } else {
        //     $setiap_hari = 1;
        // }

        $pengingat = new \App\pengingat();
        $pengingat->insert(['pengingat'=>$request->judul,'keterangan'=>$request->keterangan, 'tanggal'=>$tanggal, 'jam'=>$request->jam, 'user'=>$user_id, 'created_at'=>date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]);
        return redirect()->to(route('pengingat'))->with('success','Berhasil Menambahkan Data');
    }

    public function edit($id)
    {
        $this->fill();
        $data = $this->getMenuAll();

        $pengingat = new \App\pengingat();
        $data['data_pengingat'] = $pengingat->where('id',$id)->get();
        return view('admin.pengingat.edit',$data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul'  => 'required',
            'keterangan'   => 'required',
            'tanggal'   => 'required',
            'jam'   => 'required',
        ]);

        $tanggal = date("Y-m-d", strtotime($request->tanggal));
        $user_id = Auth::user()->id;

        $pengingat = pengingat::find($request->id);
        $pengingat->pengingat = $request->judul;
        $pengingat->keterangan = $request->keterangan;
        $pengingat->tanggal = $tanggal;
        $pengingat->jam = $request->jam;
        $pengingat->save();
        return redirect()->to(route('pengingat'))->with('success','Berhasil Menambahkan Data');
    }

    public function delete(Request $request){
        $pengingat = pengingat::find($request->id);
        $pengingat->delete();
        return redirect()->to(route('pengingat'))->with('success','Berhasil Menghapus Data');
    }
}
