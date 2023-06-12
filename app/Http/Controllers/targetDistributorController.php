<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\target_distributor;
use App\distributor;
use DB;
use Carbon\Carbon;
use App\Exports\TargetDistributorExport;
use Maatwebsite\Excel\Facades\Excel;
use App\daftar_user;

class targetDistributorController extends Controller
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
                        ->where('sub_menu1s.nama', 'Target Distributor')
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

    public function getMenuAll(){
        $jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();
        if ($jabatan_user[0]->nama_jabatan=="DM") {
            $target_distributor = DB::table('target_distributors')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'target_distributors.user')
                                    ->leftJoin('distributors', 'distributors.id', '=', 'target_distributors.distributor')
                                    ->select('target_distributors.*', 'daftar_users.nama_user', 'distributors.nama_distributor')
                                    ->whereNull('target_distributors.deleted_at')
                                    ->where('user',Auth::user()->id)
                                    ->get();
        } elseif($jabatan_user[0]->nama_jabatan=="Direktur" || $jabatan_user[0]->nama_jabatan=="Superuser"){
            $target_distributor = DB::table('target_distributors')
                                    ->leftJoin('daftar_users', 'daftar_users.id', '=', 'target_distributors.user')
                                    ->leftJoin('distributors', 'distributors.id', '=', 'target_distributors.distributor')
                                    ->select('target_distributors.*', 'daftar_users.nama_user', 'distributors.nama_distributor')
                                    ->whereNull('target_distributors.deleted_at')
                                    ->get();
        }
        $i=0;
        foreach($target_distributor as $target_dist) {
            // $coverage_areas = DB::table('data_distributors')
            //                     ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
            //                     ->leftJoin('distributor_areas', 'data_distributors.id', '=', 'distributor_areas.data_distributor_id')
            //                     ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'distributor_areas.coverage_area_id')
            //                     ->select('coverage_areas.nama_coverage_area')->distinct()
            //                     ->where('distributor_distributors.distributor_id', $target_dist->distributor)
            //                     ->get();
            // dd($coverage_areas);
            $coverage_areas = DB::table('user_coverage_areas')
                                ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'user_coverage_areas.coverage_area_id')
                                ->where('user_id',$target_dist->user)
                                ->select('coverage_areas.nama_coverage_area', 'coverage_areas.id')->distinct()
                                ->get();

            $coverage_area = '';
            $cabang = '';
            $produk = '';
            foreach($coverage_areas as $area) {
                if ($coverage_area != '') {
                    $coverage_area = $coverage_area.', '.$area->nama_coverage_area;
                } else {
                    $coverage_area = $coverage_area.''.$area->nama_coverage_area;
                }
                // $cabangs = DB::table('produk_cabangs')->where('produk_cabangs.coverage_area_id',$area->id)->select('produks.nama','cabangs.nama_cabang')->leftJoin('produks','produks.id','=','produk_cabangs.produk_id')->leftJoin('cabangs','cabangs.id','=','produk_cabangs.cabang_id')->get();
                // dd($cabangs);
                // foreach ($cabangs as $row) {
                //     if ($cabang != '') {
                //         $cabang = $cabang.', '.$row->nama_cabang;
                //     } else {
                //         $cabang = $cabang.''.$row->nama_cabang;
                //     }
                //     if ($produk != '') {
                //         $produk = $produk.', '.$row->nama;
                //     } else {
                //         $produk = $produk.''.$row->nama;
                //     }
                // }
            }
            $target_distributor[$i]->coverage_area = $coverage_area;

            // $cabangs = DB::table('data_distributors')
            //             ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
            //             ->leftJoin('distributor_cabangs', 'data_distributors.id', '=', 'distributor_cabangs.data_distributor_id')
            //             ->leftJoin('cabangs', 'cabangs.id', '=', 'distributor_cabangs.cabang_id')
            //             ->select('cabangs.nama_cabang')->distinct()
            //             ->where('distributor_distributors.distributor_id', $target_dist->distributor)
            //             ->whereNull('data_distributors.deleted_at')
            //             ->get();

            $target_distributor[$i]->cabang = $cabang;

            $produks = DB::table('data_distributors')
                        ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
                        ->leftJoin('distributor_produks', 'data_distributors.id', '=', 'distributor_produks.data_distributor_id')
                        ->leftJoin('produks', 'produks.id', '=', 'distributor_produks.produk_id')
                        ->select('produks.nama as nama_produk')->distinct()
                        ->where('distributor_distributors.distributor_id', $target_dist->distributor)
                        ->whereNull('data_distributors.deleted_at')
                        ->get();

            $produk = '';
            foreach ($produks as $row) {
                if ($produk != '') {
                    $produk = $produk.', '.$row->nama_produk;
                } else {
                    $produk = $produk.''.$row->nama_produk;
                }
            }

            $target_distributor[$i]->produk = $produk;

            $i++;
        }
        // dd($target_distributor);
        $dist_rni = distributor::where('nama_distributor', 'RNI')->first();
        $dist_mbs = distributor::where('nama_distributor', 'MBS')->first();
        $dist_igm = distributor::where('nama_distributor', 'IGM')->first();

        if($jabatan_user[0]->nama_jabatan=="DM"){
            $total_target_rni = DB::table('target_distributors')->where('distributor', $dist_rni->id)->where('user',Auth::user()->id)->whereNull('deleted_at')->sum('target');
            $total_target_mbs = DB::table('target_distributors')->where('distributor', $dist_mbs->id)->where('user',Auth::user()->id)->whereNull('deleted_at')->sum('target');
            $total_target_igm = DB::table('target_distributors')->where('distributor', $dist_igm->id)->where('user',Auth::user()->id)->whereNull('deleted_at')->sum('target');
        } elseif($jabatan_user[0]->nama_jabatan=="Direktur" || $jabatan_user[0]->nama_jabatan=="Superuser"){
            $total_target_rni = DB::table('target_distributors')->where('distributor', $dist_rni->id)->whereNull('deleted_at')->sum('target');
            $total_target_mbs = DB::table('target_distributors')->where('distributor', $dist_mbs->id)->whereNull('deleted_at')->sum('target');
            $total_target_igm = DB::table('target_distributors')->where('distributor', $dist_igm->id)->whereNull('deleted_at')->sum('target');
        }


        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'target_distributor'=>$target_distributor, 'target_rni'=>$total_target_rni, 'target_mbs'=>$total_target_mbs, 'target_igm'=>$total_target_igm,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_marketing.terget_distributor.index',$data);
    }
    public function add(){
        $this->fill();
        $data = $this->getMenuAll();

        $distributors = distributor::all();
        $dm = daftar_user::select('daftar_users.id', 'daftar_users.nama_user')->leftJoin('jabatans','jabatans.id','=','daftar_users.jabatan')->where('jabatans.nama_jabatan','DM')->get();
        $data['distributors'] = $distributors;
        $data['dms'] = $dm;

        return view('admin.manajemen_marketing.terget_distributor.add', $data);
    }
    public function store(Request $request){

        $request->validate([
            'dm'  => 'required',
            'bulan'  => 'required',
            'tahun'   => 'required',
            'distributor'   => 'required',
            'nilai_target'   => 'required'
        ]);
        $nilai_target = str_replace(".","",$request->nilai_target);
        $bulan = date('Y-m-d', strtotime($request->tahun.'-'.$request->bulan.'-1'));

        $target_distributor = new \App\target_distributor();
        $target_distributor->insert(['bulan'=>$bulan,'tahun'=>$request->tahun, 'user'=>$request->dm, 'distributor'=>$request->distributor, 'target'=>$nilai_target, 'created_at'=>$this->now(),'updated_at'=>$this->now()]);

        return redirect()->to(route('target-distributor'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();

        $distributors = distributor::all();
        $data['distributors'] = $distributors;

        $target_distributor = new \App\target_distributor();
        $data['data_target_distributor'] = $target_distributor->where('id',$id)->get();

        return view('admin.manajemen_marketing.terget_distributor.edit',$data);
    }
    public function update(Request $request){
        $request->validate([
            'bulan'  => 'required',
            'tahun'   => 'required',
            'distributor'   => 'required',
            'nilai_target'   => 'required'
        ]);

        $nilai_target = str_replace(".","",$request->nilai_target);
        $bulan = date('Y-m-d', strtotime($request->tahun.'-'.$request->bulan.'-1'));

        $target_distributor = target_distributor::find($request->id);
        $target_distributor->bulan = $bulan;
        $target_distributor->tahun = $request->tahun;
        $target_distributor->distributor = $request->distributor;
        $target_distributor->target = $nilai_target;
        $target_distributor->save();

        return redirect()->to(route('target-distributor'))->with('success','Berhasil Menambahkan Data');
    }
    public function delete(Request $request){
        $target_distributor = target_distributor::find($request->id);
        $target_distributor->delete();
        return redirect()->to(route('target-distributor'))->with('success','Berhasil Menghapus Data');
    }

    public function details($id)
    {
        $target_distributor = DB::table('target_distributors')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'target_distributors.user')
                        ->leftJoin('distributors', 'distributors.id', '=', 'target_distributors.distributor')
                        ->select('target_distributors.*', 'daftar_users.nama_user', 'distributors.nama_distributor')
                        ->where('target_distributors.id', $id)
                        ->whereNull('target_distributors.deleted_at')->get();

        $distributor_id = $target_distributor[0]->distributor;
        // dd($target_distributor);
        // $coverage_areas = DB::table('data_distributors')
        //                 ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
        //                 ->leftJoin('distributor_areas', 'data_distributors.id', '=', 'distributor_areas.data_distributor_id')
        //                 ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'distributor_areas.coverage_area_id')
        //                 ->select('coverage_areas.nama_coverage_area')->distinct()
        //                 ->where('distributor_distributors.distributor_id', $distributor_id)
        //                 ->whereNull('data_distributors.deleted_at')
        //                 ->get();
        $coverage_areas = DB::table('user_coverage_areas')
                        ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'user_coverage_areas.coverage_area_id')
                        ->where('user_id',$target_distributor[0]->user)
                        ->select('coverage_areas.nama_coverage_area', 'coverage_areas.id')->distinct()
                        ->get();
        // dd($coverage_areas);
        $coverage_area = '';
        foreach ($coverage_areas as $area) {
            if ($coverage_area != '') {
                $coverage_area = $coverage_area.', '.$area->nama_coverage_area;
            } else {
                $coverage_area = $coverage_area.''.$area->nama_coverage_area;
            }
        }

        $target_distributor[0]->coverage_area = $coverage_area;

        $cabangs = DB::table('data_distributors')
                        ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
                        ->leftJoin('distributor_cabangs', 'data_distributors.id', '=', 'distributor_cabangs.data_distributor_id')
                        ->leftJoin('cabangs', 'cabangs.id', '=', 'distributor_cabangs.cabang_id')
                        ->select('cabangs.nama_cabang')->distinct()
                        ->where('distributor_distributors.distributor_id', $distributor_id)
                        ->whereNull('data_distributors.deleted_at')
                        ->get();

        $cabang = '';
        foreach ($cabangs as $row) {
            if ($cabang != '') {
                $cabang = $cabang.', '.$row->nama_cabang;
            } else {
                $cabang = $cabang.''.$row->nama_cabang;
            }
        }

        $target_distributor[0]->cabang = $cabang;

        $produks = DB::table('data_distributors')
                        ->leftJoin('distributor_distributors', 'data_distributors.id', '=', 'distributor_distributors.data_distributor_id')
                        ->leftJoin('distributor_produks', 'data_distributors.id', '=', 'distributor_produks.data_distributor_id')
                        ->leftJoin('produks', 'produks.id', '=', 'distributor_produks.produk_id')
                        ->select('produks.nama as nama_produk')->distinct()
                        ->where('distributor_distributors.distributor_id', $distributor_id)
                        ->whereNull('data_distributors.deleted_at')
                        ->get();

        $produk = '';
        foreach ($produks as $row) {
            if ($produk != '') {
                $produk = $produk.', '.$row->nama_produk;
            } else {
                $produk = $produk.''.$row->nama_produk;
            }
        }

        $target_distributor[0]->produk = $produk;

        $bulan = date('F', strtotime($target_distributor[0]->bulan));
        $target_distributor[0]->bulan = $bulan;

        echo json_encode($target_distributor);
    }
    public function export()
    {
        return Excel::download(new TargetDistributorExport, 'target-distributor.xlsx');
    }
}
