<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\data_distributor;
use App\daftar_user;
use App\coverage_area;
use App\distributor;
use App\distributor_area;
use App\distributor_cabang;
use App\distributor_produk;
use App\distributor_distributor;
use App\cabang;
use App\produk;
use DB;
use Carbon\Carbon;
use App\Exports\DataDistributorExport;
use Maatwebsite\Excel\Facades\Excel;

class dataDistributorController extends Controller
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
                        ->where('sub_menu1s.nama', 'Data Distributor')
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
        $data_distributors = DB::table('produk_cabangs')
                                ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                                ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
                                ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'produk_cabangs.coverage_area_id')
                                ->leftJoin('cabangs', 'cabangs.id', '=', 'produk_cabangs.cabang_id')
                                ->leftJoin('user_coverage_areas', 'user_coverage_areas.coverage_area_id', '=', 'coverage_areas.id')
                                ->leftJoin('daftar_users', 'daftar_users.id', '=', 'user_coverage_areas.user_id')
                                ->select('daftar_users.nama_user', 'produks.nama as nama_produk', 'distributors.nama_distributor', 'coverage_areas.nama_coverage_area', 'cabangs.nama_cabang')->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'data_distributors'=>$data_distributors,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_distributor.data_distributor.index',$data);
    }
    public function add(){
        $this->fill();
        $data = $this->getMenuAll();

        $users = daftar_user::all();
        $data['users'] = $users;

        $coverage_areas = coverage_area::all();
        $data['coverage_areas'] = $coverage_areas;

        $distributors = distributor::all();
        $data['distributors'] = $distributors;

        $cabang = cabang::all();
        $data['cabangs'] = $cabang;

        $produks = produk::all();
        $data['produks'] = $produks;

        return view('admin.manajemen_distributor.data_distributor.add',$data);
    }
    public function store(Request $request){
        $request->validate([
            'user'  => 'required',
            'areas'   => 'required',
            'distributors'   => 'required',
            'cabangs'   => 'required',
            'produks'   => 'required',
        ]);

        $data_distributor_id = DB::table('data_distributors')->insertGetId(
            array('created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d'), 'users' => $request->user)
        );

        for ($i=0; $i<count($request->areas); $i++) {
            $dist_area = new distributor_area;
            $dist_area->data_distributor_id = $data_distributor_id;
            $dist_area->coverage_area_id = $request->areas[$i];
            $dist_area->save();
        }

        for ($i=0; $i<count($request->cabangs); $i++) {
            $dist_cabang = new distributor_cabang;
            $dist_cabang->data_distributor_id = $data_distributor_id;
            $dist_cabang->cabang_id = $request->cabangs[$i];
            $dist_cabang->save();
        }

        for ($i=0; $i<count($request->produks); $i++) {
            $dist_produk = new distributor_produk;
            $dist_produk->data_distributor_id = $data_distributor_id;
            $dist_produk->produk_id = $request->produks[$i];
            $dist_produk->save();
        }

        for ($i=0; $i<count($request->distributors); $i++) {
            $dist_distributor = new distributor_distributor;
            $dist_distributor->data_distributor_id = $data_distributor_id;
            $dist_distributor->distributor_id = $request->distributors[$i];
            $dist_distributor->save();
        }

        return redirect()->to(route('data-distributor'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();

        $users = daftar_user::all();
        $data['users'] = $users;

        $coverage_areas = coverage_area::all();
        $data['coverage_areas'] = $coverage_areas;

        $data_distributor_area = distributor_area::where('data_distributor_id', $id)->get();
        $data['data_distributor_area_selected'] = $data_distributor_area;

        $distributors = distributor::all();
        $data['distributors'] = $distributors;

        $data_distributor_distributor = distributor_distributor::where('data_distributor_id', $id)->get();
        $data['data_distributor_distributor_selected'] = $data_distributor_distributor;

        $cabang = cabang::all();
        $data['cabangs'] = $cabang;

        $data_distributor_cabang = distributor_cabang::where('data_distributor_id', $id)->get();
        $data['data_distributor_cabang_selected'] = $data_distributor_cabang;

        $produks = produk::all();
        $data['produks'] = $produks;

        $data_distributor_produk = distributor_produk::where('data_distributor_id', $id)->get();
        $data['data_distributor_produk_selected'] = $data_distributor_produk;

        $data_distributor = new \App\data_distributor();
        $data['data_distributor'] = $data_distributor->where('id',$id)->get();
        return view('admin.manajemen_distributor.data_distributor.edit',$data);
    }
    public function update(Request $request){
        $request->validate([
            'user'  => 'required',
            'areas'   => 'required',
            'distributors'   => 'required',
            'cabangs'   => 'required',
            'produks'   => 'required',
        ]);

        $data_distributor = data_distributor::find($request->id);
        $data_distributor->users = $request->user;
        $data_distributor->save();

        $delete_distributor_area = distributor_area::where('data_distributor_id', $request->id);
        $delete_distributor_area->delete();

        $delete_distributor_cabang = distributor_cabang::where('data_distributor_id', $request->id);
        $delete_distributor_cabang->delete();

        $delete_distributor_distributor = distributor_distributor::where('data_distributor_id', $request->id);
        $delete_distributor_distributor->delete();

        $delete_distributor_produk = distributor_produk::where('data_distributor_id', $request->id);
        $delete_distributor_produk->delete();

        for ($i=0; $i<count($request->areas); $i++) {
            $dist_area = new distributor_area;
            $dist_area->data_distributor_id = $request->id;
            $dist_area->coverage_area_id = $request->areas[$i];
            $dist_area->save();
        }

        for ($i=0; $i<count($request->cabangs); $i++) {
            $dist_cabang = new distributor_cabang;
            $dist_cabang->data_distributor_id = $request->id;
            $dist_cabang->cabang_id = $request->cabangs[$i];
            $dist_cabang->save();
        }

        for ($i=0; $i<count($request->produks); $i++) {
            $dist_produk = new distributor_produk;
            $dist_produk->data_distributor_id = $request->id;
            $dist_produk->produk_id = $request->produks[$i];
            $dist_produk->save();
        }

        for ($i=0; $i<count($request->distributors); $i++) {
            $dist_distributor = new distributor_distributor;
            $dist_distributor->data_distributor_id = $request->id;
            $dist_distributor->distributor_id = $request->distributors[$i];
            $dist_distributor->save();
        }

        return redirect()->to(route('data-distributor'))->with('success','Berhasil Menambahkan Data');
    }
    public function delete(Request $request){
        $data_distributor = data_distributor::find($request->id);
        $data_distributor->delete();
        return redirect()->to(route('data-distributor'))->with('success','Berhasil Menghapus Data');
    }
    public function export()
    {
        return Excel::download(new DataDistributorExport, 'data-distributor.xlsx');
    }

    public function getDataRelation($id)
    {
        $coverage_areas = DB::table('user_coverage_areas')
                            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'user_coverage_areas.coverage_area_id')
                            ->select('user_coverage_areas.*', 'coverage_areas.nama_coverage_area')
                            ->where('user_coverage_areas.user_id', $id)->get();
        
        $data['coverage_areas'] = $coverage_areas;

        echo json_encode($data);
    }
}
