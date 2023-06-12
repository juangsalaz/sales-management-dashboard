<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\daftar_user;
use App\running_text;
use DB;
use Carbon\Carbon;

class settingsController extends Controller
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
        $sub_menu = new \App\sub_menu1();
        $this->daftar_sub_menu2 = $sub_menu->select('sub_menu2s.nama','sub_menu2s.slug')->where('sub_menu1s.nama','Daftar Bagian')->leftJoin('sub_menu2s','sub_menu1s.id','=','sub_menu2s.id_sub_menu1')->get();
    }
    public function fill(){
        $this->getJabatan();
        $this->getMenuJabatan();
        $this->getSubMenu();
        $this->getSubMenu2();
    }
    public function getMenuAll(){$jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function editPassword(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.setting.edit_password.edit_password',$data);
    }

    public function editPasswordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed'
        ]);

        $user_id = Auth::user()->id;

        $daftar_user = daftar_user::find($user_id);
        $daftar_user->password = Hash::make($request->password);
        $daftar_user->save();

        return redirect()->to(route('setting-edit-password'))->with('success','Berhasil Menambahkan Data');
    }

    public function runningText()
    {
        $this->fill();
        $data = $this->getMenuAll();

        $running_text = running_text::all();
        $data['running_text'] = $running_text;

        return view('admin.setting.running_text',$data);
    }

    public function runningTextUpdate(Request $request)
    {
        $running_text = running_text::all();
        $id = $running_text[0]->id;

        $daftar_user = running_text::find($id);
        $daftar_user->teks = $request->teks;
        $daftar_user->save();

        return redirect()->to(route('setting-running-text'))->with('success','Berhasil Menambahkan Data');
    }
}
