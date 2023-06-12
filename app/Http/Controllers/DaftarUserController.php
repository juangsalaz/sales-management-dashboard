<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\daftar_user;
use DB;
use Carbon\Carbon;
use App\Exports\DaftarUserExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class DaftarUserController extends Controller
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
                        ->where('sub_menu1s.nama', 'Daftar User')
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
        $users = new \App\daftar_user();
        $data_user = $users->select('daftar_users.id','nama_user','username','password','bagian','jabatan','nama_bagian','nama_jabatan','status')->leftJoin('bagians','bagian','=','bagians.id')->leftJoin('jabatans','jabatan','=','jabatans.id')->get();

        $i=0;
        foreach($data_user as $row) {
            $coverage_areas = DB::table('user_coverage_areas')
                                ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'user_coverage_areas.coverage_area_id')
                                ->select('coverage_areas.nama_coverage_area')
                                ->where('user_coverage_areas.user_id', $row->id)
                                ->get();
            $data_user[$i]->coverage_area = $coverage_areas;

            $i++;
        }

        $bagians = new \App\bagian();
        $data_bagian = $bagians->all();
        $jabatans = new \App\jabatan();
        $data_jabatan = $jabatans->all();
        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();
        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'users'=>$data_user,'jabatans'=>$data_jabatan,'bagians'=>$data_bagian,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_user.daftar_user',$data);
    }
    public function tambah(){
        $this->fill();
        $data = $this->getMenuAll();

        $coverage_areas = DB::table('coverage_areas')->select('*')->whereNull('deleted_at')->get();
        $data['coverage_areas'] = $coverage_areas;

        return view('admin.manajemen_user.daftar_user_add',$data);
    }
    public function store(Request $request){

        if ($request->has('areas')) {
            $request->validate([
                        'nama_user'  => 'required',
                        'username'  => 'required',
                        'password'  => 'required|required_with:password_confirmation|confirmed',
                        'bagian'  => 'required',
                        'jabatan'  => 'required',
                        'status'  => 'required',
                        'areas.1'  => 'required',
                    ]);
        } else {
            $request->validate([
                'nama_user'  => 'required',
                'username'  => 'required',
                'password'  => 'required|required_with:password_confirmation|confirmed',
                'bagian'  => 'required',
                'jabatan'  => 'required',
                'status'  => 'required',
            ]);
        }

        $user_id = DB::table('daftar_users')->insertGetId(
            array('id' => Str::uuid(), 'nama_user'=>$request['nama_user'],'username'=>$request['username'],'password'=>Hash::make($request['password']),'bagian'=>$request['bagian'],'jabatan'=>$request['jabatan'],'status'=>$request['status'],'created_at'=>$this->now(),'updated_at'=>$this->now())
        );

        if ($request->has('areas')) {
            for ($i=1; $i < count($request->areas); $i++) { 
                DB::table('user_coverage_areas')->insert(
                    ['id' => Str::uuid(), 'user_id' => $user_id, 'coverage_area_id' => $request->areas[$i], 'created_at' => $this->now(), 'updated_at' => $this->now()]
                );
            }
        }
        
        return redirect()->to(route('daftar-user'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();
        $users = new \App\daftar_user();
        $data['data_user'] = $users->where('id',$id)->get();

        $coverage_areas = DB::table('coverage_areas')->select('*')->whereNull('deleted_at')->get();
        $data['coverage_areas'] = $coverage_areas;

        $selected_coverage_area = DB::table('user_coverage_areas')
                                    ->select('user_coverage_areas.*')
                                    ->where('user_coverage_areas.user_id', $id)->get();
        $data['selected_coverage_area'] = $selected_coverage_area;

        return view('admin.manajemen_user.daftar_user_edit', $data);
    }
    public function update(Request $request){
        if ($request->has('areas')) {
            $request->validate([
                        'nama_user'  => 'required',
                        'username'  => 'required',
                        'password'  => 'required|required_with:password_confirmation|confirmed',
                        'bagian'  => 'required',
                        'jabatan'  => 'required',
                        'status'  => 'required',
                        'areas.1'  => 'required',
                    ]);
        } else {
            $request->validate([
                'nama_user'  => 'required',
                'username'  => 'required',
                'password'  => 'required|required_with:password_confirmation|confirmed',
                'bagian'  => 'required',
                'jabatan'  => 'required',
                'status'  => 'required',
            ]);
        }

        $user = \App\daftar_user::find($request->id);
        $user->nama_user = $request->nama_user;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->bagian = $request->bagian;
        $user->jabatan = $request->jabatan;
        $user->status = $request->status;
        $user->save();

        DB::table('user_coverage_areas')->where('user_id', $request->id)->delete();
        if ($request->has('areas')) {
            for ($i=1; $i < count($request->areas); $i++) { 
                DB::table('user_coverage_areas')->insert(
                    ['user_id' => $request->id, 'coverage_area_id' => $request->areas[$i], 'created_at' => $this->now(), 'updated_at' => $this->now()]
                );
            }
        }
        return redirect()->to(route('daftar-user'))->with('success','Berhasil Menambahkan Data');
    }
    public function delete(Request $request){
        $user = \App\daftar_user::find($request->id);
        $user->delete();
        return redirect()->to(route('daftar-user'))->with('success','Berhasil Menghapus Data');
    }
    public function export()
    {
        return Excel::download(new DaftarUserExport, 'daftar-user.xlsx');
    }
}
