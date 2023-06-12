<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\daftar_user;
use DB;
use Carbon\Carbon;
use App\Exports\OtoritasMenuExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class OtoritasMenuController extends Controller
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
                        ->where('sub_menu1s.nama', 'Otoritas Menu')
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
        $jabatan = new \App\jabatan();
        $jabatans = $jabatan->all();
        $menu = new \App\menu_utama();
        $menus = $menu->all();
        $menu_jabatan = new \App\menu_jabatan();
        $menu_jabatans = $menu_jabatan->all();
        $jabatan_otoritas = $menu_jabatan->select('id_jabatan','jabatans.nama_jabatan as nama')->leftJoin('jabatans','jabatans.id','=','id_jabatan')->distinct()->get();
        $data_menu_otoritas = $menu_jabatan->select('id_jabatan', 'jabatans.nama_jabatan','id_menu','menu_utamas.nama as nama', 'menu_utamas.urutan')->leftJoin('jabatans','jabatans.id','=','id_jabatan')->leftJoin('menu_utamas','menu_utamas.id','=','id_menu')->whereRaw('id_jabatan = jabatans.id and menu_jabatans.id_sub_menu1 is null')->groupBy('id_jabatan','jabatans.nama_jabatan','id_menu','menu_utamas.nama','menu_utamas.urutan')->orderBy('menu_utamas.urutan','asc')->get();
        $data_sub_menu_otoritas = $menu_jabatan->select('id_jabatan', 'jabatans.nama_jabatan','id_sub_menu1','sub_menu1s.nama as nama')->leftJoin('jabatans','jabatans.id','=','id_jabatan')->leftJoin('sub_menu1s','sub_menu1s.id','=','id_sub_menu1')->whereRaw('id_jabatan = jabatans.id and menu_jabatans.id_sub_menu1 is not null')->groupBy('id_jabatan','jabatans.nama_jabatan','id_sub_menu1','sub_menu1s.nama')->get();
        $sub_menu1 = new \App\sub_menu1();
        $sub_menu1s = $sub_menu1->all();
        $sub_menu2 = new \App\sub_menu2();
        $sub_menu2s = $sub_menu2->all();
        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();
        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'menus'=>$menus,'sub_menu1s'=>$sub_menu1s,'sub_menu2s'=>$sub_menu2s,'jabatan_otoritas'=>$jabatan_otoritas,'data_sub_menu_otoritas'=>$data_sub_menu_otoritas,'data_menu_otoritas'=>$data_menu_otoritas,'jabatans'=>$jabatans,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);


        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_user.otoritas_menu',$data);
    }
    public function tambah(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_user.otoritas_menu_add',$data);
    }
    public function store(Request $request){
        $menu_jabatans = new \App\menu_jabatan();
        $sub_menu1s = new \App\sub_menu1();
        $sub_menu2s = new \App\sub_menu2();

        if($request['menu_utama']!=null){
            foreach ($request['menu_utama'] as $menu) {
                $first=1;
                $menu_jabatans->insert(['id' => Str::uuid(), 'id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                if($request['sub_menu1']!=null){
                    foreach ($request['sub_menu1'] as $sub) {
                        $sub_menu = $sub_menu1s->select('id','id_menu_utama')->where('id_menu_utama','=',$menu)->where('id','=',$sub)->get();
                        $second=1;
                        if($first==1){
                            if ($sub_menu->isEmpty()) {
                                continue;
                            }else{
                                if($sub==$sub_menu[0]['id']){
                                    $menu_jabatans->where('id_menu','=',$menu)->where('id_jabatan','=',$request['jabatan_id'])->update(['id_sub_menu1'=>$sub_menu[0]['id']]);
                                    if (isset($request['sub_menu2'])) {
                                        foreach ($request['sub_menu2'] as $sub2) {
                                            $sub_menu2 = $sub_menu2s->select('id','id_sub_menu1')->where('id_sub_menu1','=',$sub_menu[0]['id'])->where('id','=',$sub2)->get();
                                            if($second == 1){
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                } else {
                                                    if($sub2==$sub_menu2[0]['id']){
                                                        $menu_jabatans->where('id_menu','=',$menu)->where('id_jabatan','=',$request['jabatan_id'])->where('id_sub_menu1','=',$sub_menu[0]['id'])->update(['id_sub_menu2'=>$sub_menu2[0]['id']]);
                                                        $second=0;
                                                    }
                                                }
                                            } else {
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                } else {
                                                    if($sub2==$sub_menu2[0]['id']){
                                                        $menu_jabatans->insert(['id' => Str::uuid(), 'id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'id_sub_menu1'=>$sub_menu[0]['id'],'id_sub_menu2'=>$sub_menu2[0]['id'],'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $first=0;
                            }
                        } else {
                            if ($sub_menu->isEmpty()) {
                                continue;
                            }else{
                                if($sub==$sub_menu[0]['id']){
                                    $menu_jabatans->insert(['id' => Str::uuid(), 'id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'id_sub_menu1'=>$sub_menu[0]['id'],'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                                    if (isset($request['sub_menu2'])) {
                                        foreach ($request['sub_menu2'] as $sub2) {
                                            $sub_menu2 = $sub_menu2s->select('id','id_sub_menu1')->where('id_sub_menu1','=',$sub_menu[0]['id'])->where('id','=',$sub2)->get();
                                            if($second == 1){
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                }else{
                                                    $menu_jabatans->where('id_menu','=',$menu)->where('id_jabatan','=',$request['jabatan_id'])->where('id_sub_menu1','=',$sub_menu[0]['id'])->update(['id_sub_menu2'=>$sub_menu2[0]['id']]);
                                                    $second=0;
                                                }
                                            } else {
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                }else{
                                                    $menu_jabatans->insert(['id' => Str::uuid(), 'id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'id_sub_menu1'=>$sub_menu[0]['id'],'id_sub_menu2'=>$sub_menu2[0]['id'],'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            }
            return redirect()->to(route('otoritas-menu'))->with('success', 'Berhasil Menambahkan Data!');
        } else {
            return redirect()->back()->with('failed','Data Tidak Berhasil Ditambahkan');
        }
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();
        $a_menu = array();
        $a_sub_menu1 = array();
        $a_sub_menu2 = array();
        $menu_jabatans = new \App\menu_jabatan();
        $jabatans = new \App\jabatan();
        $jabatan_edit = $jabatans->where('id',$id)->get();
        $menu_edit = $menu_jabatans->select('id_menu')->where('id_jabatan',$id)->distinct()->get();
        foreach ($menu_edit as $value) {
            array_push($a_menu,$value['id_menu']);
        }
        $sub_menu1_edit = $menu_jabatans->select('id_sub_menu1')->where('id_jabatan',$id)->distinct()->get();
        foreach ($sub_menu1_edit as $value2) {
            array_push($a_sub_menu1,$value2['id_sub_menu1']);
            // print_r($value2['id_sub_menu1']);
        }
        $sub_menu2_edit = $menu_jabatans->select('id_sub_menu2')->where('id_jabatan',$id)->distinct()->get();
        foreach ($sub_menu2_edit as $value3) {
            array_push($a_sub_menu2,$value3['id_sub_menu2']);
        }
        $data['data_edit'] = $jabatan_edit;
        $data['data_menu_edit'] = $a_menu;
        $data['data_sub_menu1_edit'] = $a_sub_menu1;
        $data['data_sub_menu2_edit'] = $a_sub_menu2;
        // dd($sub_menu1_edit);
        // dd($data['data_sub_menu1_edit']);
        return view('admin.manajemen_user.otoritas_menu_edit',$data);
    }
    public function update(Request $request){
        $menu_jabatans = new \App\menu_jabatan();
        $sub_menu1s = new \App\sub_menu1();
        $sub_menu2s = new \App\sub_menu2();
        if($request['menu_utama']!=null){
            $menu_jabatans->where('id_jabatan','=',$request['jabatan_id'])->delete();
            foreach ($request['menu_utama'] as $menu) {
                $first=1;
                $menu_jabatans->insert(['id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                if($request['sub_menu1']!=null){
                    foreach ($request['sub_menu1'] as $sub) {
                        $sub_menu = $sub_menu1s->select('id','id_menu_utama')->where('id_menu_utama','=',$menu)->where('id','=',$sub)->get();
                        $second=1;
                        if($first==1){
                            if ($sub_menu->isEmpty()) {
                                continue;
                            }else{
                                if($sub==$sub_menu[0]['id']){
                                    $menu_jabatans->where('id_menu','=',$menu)->where('id_jabatan','=',$request['jabatan_id'])->update(['id_sub_menu1'=>$sub_menu[0]['id']]);
                                    if (isset($request['sub_menu2'])) {
                                        foreach ($request['sub_menu2'] as $sub2) {
                                            $sub_menu2 = $sub_menu2s->select('id','id_sub_menu1')->where('id_sub_menu1','=',$sub_menu[0]['id'])->where('id','=',$sub2)->get();
                                            if($second == 1){
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                } else {
                                                    if($sub2==$sub_menu2[0]['id']){
                                                        $menu_jabatans->where('id_menu','=',$menu)->where('id_jabatan','=',$request['jabatan_id'])->where('id_sub_menu1','=',$sub_menu[0]['id'])->update(['id_sub_menu2'=>$sub_menu2[0]['id']]);
                                                        $second=0;
                                                    }
                                                }
                                            } else {
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                } else {
                                                    if($sub2==$sub_menu2[0]['id']){
                                                        $menu_jabatans->insert(['id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'id_sub_menu1'=>$sub_menu[0]['id'],'id_sub_menu2'=>$sub_menu2[0]['id'],'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $first=0;
                            }
                        } else {
                            if ($sub_menu->isEmpty()) {
                                continue;
                            }else{
                                if($sub==$sub_menu[0]['id']){
                                    $menu_jabatans->insert(['id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'id_sub_menu1'=>$sub_menu[0]['id'],'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                                    if (isset($request['sub_menu2'])) {
                                        foreach ($request['sub_menu2'] as $sub2) {
                                            $sub_menu2 = $sub_menu2s->select('id','id_sub_menu1')->where('id_sub_menu1','=',$sub_menu[0]['id'])->where('id','=',$sub2)->get();
                                            if($second == 1){
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                }else{
                                                    $menu_jabatans->where('id_menu','=',$menu)->where('id_jabatan','=',$request['jabatan_id'])->where('id_sub_menu1','=',$sub_menu[0]['id'])->update(['id_sub_menu2'=>$sub_menu2[0]['id']]);
                                                    $second=0;
                                                }
                                            } else {
                                                if ($sub_menu2->isEmpty()) {
                                                    continue;
                                                }else{
                                                    $menu_jabatans->insert(['id_jabatan'=>$request['jabatan_id'],'id_menu'=>$menu,'id_sub_menu1'=>$sub_menu[0]['id'],'id_sub_menu2'=>$sub_menu2[0]['id'],'created_at'=>$this->now(),'updated_at'=>$this->now()]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            }
            return redirect()->to(route('otoritas-menu'))->with('success', 'Berhasil Mengubah Data!');
        } else {
            return redirect()->back()->with('failed','Data Tidak Berhasil Diubah');
        }
    }
    public function delete($id){
        $menu_jabatans = new \App\menu_jabatan();
        $menu_jabatans->where('id_jabatan','=',$id)->delete();
        return redirect()->to(route('otoritas-menu'))->with('success','Berhasil Menghapus Data');
    }
    public function export()
    {
        return Excel::download(new OtoritasMenuExport, 'otoritas-menu.xlsx');
    }

}
