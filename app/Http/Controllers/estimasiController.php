<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\estimasi;
use App\outlet;
use App\produk;
use App\distributor;
use DB;
use Carbon\Carbon;
use App\Exports\EstimasiExport;
use Maatwebsite\Excel\Facades\Excel;

class estimasiController extends Controller
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
                        ->where('sub_menu1s.nama', 'Estimasi')
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

    public function countDistributorProduct($id, $dm=null, $awal=null, $akhir=null)
    {
        $query = DB::table('estimasis')
                                ->select('estimasis.*')
                                ->where('estimasis.distributor_id', $id)
                                ->where('status','Diterima')
                                ->whereNull('deleted_at');
        if ($dm!=null) {
            $query->where('estimasis.nama_user', $dm);
        }
        if ($awal != null and $akhir != null) {
            $awal = date('Y-m-d', strtotime($awal));
            $akhir = date('Y-m-d', strtotime($akhir));
            $query->whereBetween('estimasis.bulan', [$awal, $akhir]);
        }
        $array_product = $query->get();

        $total_value_nett = 0;
        $total_value_gross = 0;
        $total_diskon = 0;
        $total_data = count($array_product);
        $total_diskon_value = 0;
        foreach ($array_product as $row) {
            $total_value_nett = $total_value_nett+$row->value_nett;
            $total_value_gross = $total_value_gross+$row->value_gross;
            $total_diskon = $total_diskon+$row->diskon;
            $total_diskon_value = $total_diskon_value+$row->diskon_value;
        }

        $rata_diskon = 0;
        if ($total_data != 0) {
            $rata_diskon = $total_diskon/$total_data;
        }

        $array_data = array('total_product'=>count($array_product), 'total_value_nett'=>$total_value_nett, 'total_value_gross'=>$total_value_gross, 'rata_diskon'=>$rata_diskon, 'total_diskon_value'=>$total_diskon_value);

        return $array_data;
    }

    public function getMenuAll($dm = null, $bulan=null, $tahun=null){
        $awal=null;
        $akhir=null;
        $jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();

        if ($jabatan_user[0]->nama_jabatan=="DM") {
            $query = DB::table('estimasis')
                ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                ->leftJoin('outlets', 'outlets.id', '=', 'estimasis.outlet')
                ->leftJoin('produks', 'produks.id', '=', 'estimasis.produk')
                ->select('estimasis.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                ->whereNull('estimasis.deleted_at')
                ->where('estimasis.nama_user',Auth::user()->id)
                ->orderBy('estimasis.created_at','desc');
            if ($bulan != null and $tahun != null) {
                $awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-1'));
                $akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-1'));
                $query->whereBetween('estimasis.bulan', [$awal, $akhir]);
            }
            $estimasis = $query->get();
        } elseif($jabatan_user[0]->nama_jabatan=="Direktur" || $jabatan_user[0]->nama_jabatan=="Superuser") {
                $query = DB::table('estimasis')
                                ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                                ->leftJoin('outlets', 'outlets.id', '=', 'estimasis.outlet')
                                ->leftJoin('produks', 'produks.id', '=', 'estimasis.produk')
                                ->select('estimasis.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                                ->whereNull('estimasis.deleted_at')
                                ->orderBy('estimasis.created_at','desc');
                if($dm != null){
                    $query->where('estimasis.nama_user',$dm);
                }
                if ($bulan != null and $tahun != null) {
                    $awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-1'));
                    $akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-1'));
                    $query->whereBetween('estimasis.bulan', [$awal, $akhir]);
                }
                $estimasis = $query->get();
        }

        $dist_rni = distributor::where('nama_distributor', 'RNI')->first();
        $dist_mbs = distributor::where('nama_distributor', 'MBS')->first();
        $dist_igm = distributor::where('nama_distributor', 'IGM')->first();
        if ($jabatan_user[0]->nama_jabatan=="DM") {
            if ($bulan != null and $tahun != null) {
                $awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-1'));
                $akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-1'));
            }
            $total_rni_product = $this->countDistributorProduct($dist_rni->id,Auth::user()->id, $awal, $akhir);
            $total_mbs_product = $this->countDistributorProduct($dist_mbs->id,Auth::user()->id, $awal, $akhir);
            $total_igm_product = $this->countDistributorProduct($dist_igm->id,Auth::user()->id, $awal, $akhir);

        } elseif($jabatan_user[0]->nama_jabatan=="Direktur" || $jabatan_user[0]->nama_jabatan=="Superuser"){

            if ($bulan != null and $tahun != null) {

                $awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-1'));
                $akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-1'));
            }
                $total_rni_product = $this->countDistributorProduct($dist_rni->id,$dm, $awal, $akhir);
                $total_mbs_product = $this->countDistributorProduct($dist_mbs->id,$dm, $awal, $akhir);
                $total_igm_product = $this->countDistributorProduct($dist_igm->id,$dm, $awal, $akhir);

        }

        $jabatan_dm = DB::table('jabatans')
                            ->select('jabatans.*')
                            ->where('nama_jabatan', 'DM')->get();

        $dm_id = $jabatan_dm[0]->id;

        $data_dm = DB::table('daftar_users')
                        ->select('daftar_users.*')
                        ->whereNull('deleted_at')
                        ->where('daftar_users.jabatan', $dm_id)->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        $data = array('jabatan_pengguna'=>$jabatan_user[0]->nama_jabatan,'pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'estimasis'=>$estimasis, 'total_rni'=>$total_rni_product, 'total_igm'=>$total_igm_product, 'total_mbs'=>$total_mbs_product,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count, 'data_dm'=>$data_dm);
        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(Request $request){
        session(['dm_estimasi' => $request->user_dm,'bulan_estimasi'=>$request->bulan,'tahun_estimasi'=>$request->tahun]);
        $this->fill();
        if ($request->user_dm==null and $request->bulan==null and $request->tahun==null) {
            $data = $this->getMenuAll();
        } else {
            if($request->bulan!=null or $request->tahun!=null){
                $request->validate([
                    'bulan'  => 'required',
                    'tahun'  => 'required'
                ]);
            }
            // dd($request->bulan);
            $data = $this->getMenuAll($request->user_dm, $request->bulan, $request->tahun);
        }
        return view('admin.manajemen_marketing.estimasi.index',$data);
    }
    public function add(){
        $this->fill();
        $data = $this->getMenuAll();

        $outlets = outlet::all();
        $data['outlets'] = $outlets;

        $produks = produk::all();
        $data['produks'] = $produks;

        return view('admin.manajemen_marketing.estimasi.add',$data);
    }
    public function store(Request $request){

        $request->validate([
            'bulan'  => 'required',
            'tahun'   => 'required',
            'outlet'   => 'required',
            'produk'   => 'required',
            'harga_produk'   => 'required',
            'kuantiti'   => 'required',
            'value_gross'   => 'required',
            'diskon'   => 'required',
            'diskon_value'   => 'required',
            'value_nett'   => 'required',
            'status'   => 'required',
            'user' => 'required',
            'coverage_area' => 'required',
            'cabang' => 'required',
            'distributor' => 'required'
        ]);

        $diskon = str_replace(",",".",$request->diskon);
        $kuantiti = str_replace(".","",$request->kuantiti);
        $harga = str_replace(".","",$request->harga_produk);
        $value_gross = str_replace(".","",$request->value_gross);
        $diskon_value = str_replace(".","",$request->diskon_value);
        $value_net = str_replace(".","",$request->value_nett);

        $bulan = date('Y-m-d', strtotime($request->tahun.'-'.$request->bulan.'-1'));
        $estimasi = new \App\estimasi();
        $estimasi->insert(['bulan'=>$bulan,'tahun'=>$request->tahun, 'nama_user'=>$request->user, 'outlet'=>$request->outlet, 'produk'=>$request->produk, 'harga_produk'=>$harga, 'kuantiti'=>$kuantiti, 'value_gross'=>$value_gross, 'diskon'=>$diskon, 'diskon_value'=>$diskon_value, 'value_nett'=>$value_net, 'status'=>$request->status,'created_at'=>$this->now(),'updated_at'=>$this->now(), 'coverage_area_id'=>$request->coverage_area, 'cabang_id'=>$request->cabang, 'distributor_id'=>$request->distributor]);

        $last = DB::table('estimasis')->where('nama_user','=',$request->user)->orderBy('created_at','desc')->first();
        $notifikasi = new \App\notifikasi();
        $notifikasi->insert(['id_user'=>$request->user,'id_estimasi'=>$last->id,'read'=>0,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);

        return redirect()->to(route('estimasi'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();

        $outlets = outlet::all();
        $data['outlets'] = $outlets;

        $produks = produk::all();
        $data['produks'] = $produks;

        $estimasi = new \App\estimasi();
        $data_estimasi = $estimasi->where('id',$id)->get();
        $data['data_estimasi'] = $data_estimasi;

        $coverage_areas = DB::table('user_coverage_areas')
                            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'user_coverage_areas.coverage_area_id')
                            ->select('coverage_areas.id', 'coverage_areas.nama_coverage_area')
                            ->where('user_coverage_areas.user_id', $data_estimasi[0]->nama_user)->get();
        $data['coverage_area_edit'] = $coverage_areas;

        $cabangs = DB::table('cabangs')
                            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'cabangs.coverage_area_id')
                            ->select('cabangs.id', 'cabangs.nama_cabang')
                            ->where('cabangs.coverage_area_id', $coverage_areas[0]->id)->get();
        $data['cabangs'] = $cabangs;

        $distributors = DB::table('produk_cabangs')
                                ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                                ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
                                ->select('distributors.id', 'distributors.nama_distributor')
                                ->where('produk_cabangs.cabang_id', $cabangs[0]->id)->get();

        $data['distributors'] = $distributors;

        return view('admin.manajemen_marketing.estimasi.edit',$data);
    }
    public function update(Request $request){
        $request->validate([
            'bulan'  => 'required',
            'tahun'   => 'required',
            'outlet'   => 'required',
            'produk'   => 'required',
            'harga_produk'   => 'required',
            'kuantiti'   => 'required',
            'value_gross'   => 'required',
            'diskon'   => 'required',
            'diskon_value'   => 'required',
            'value_nett'   => 'required',
            'status'   => 'required',
            'user' => 'required',
            'coverage_area' => 'required',
            'cabang' => 'required',
            'distributor' => 'required'
        ]);

        $diskon = str_replace(",",".",$request->diskon);
        $kuantiti = str_replace(".","",$request->kuantiti);
        $harga = str_replace(".","",$request->harga_produk);
        $value_gross = str_replace(".","",$request->value_gross);
        $diskon_value = str_replace(".","",$request->diskon_value);
        $value_net = str_replace(".","",$request->value_nett);

        $bulan = date('Y-m-d', strtotime($request->tahun.'-'.$request->bulan.'-1'));

        $estimasi = estimasi::find($request->id);
        $estimasi->bulan = $bulan;
        $estimasi->tahun = $request->tahun;
        $estimasi->nama_user = $request->user;
        $estimasi->coverage_area_id = $request->coverage_area;
        $estimasi->cabang_id = $request->cabang;
        $estimasi->distributor_id = $request->distributor;
        $estimasi->outlet = $request->outlet;
        $estimasi->produk = $request->produk;
        $estimasi->harga_produk = $harga;
        $estimasi->kuantiti = $kuantiti;
        $estimasi->value_gross = $value_gross;
        $estimasi->diskon = $diskon;
        $estimasi->diskon_value = $diskon_value;
        $estimasi->value_nett = $value_net;
        $estimasi->status = $request->status;
        $estimasi->save();

        return redirect()->to(route('estimasi'))->with('success','Berhasil Menambahkan Data');
    }
    public function delete(Request $request){
        $estimasi = estimasi::find($request->id);
        $estimasi->delete();
        return redirect()->to(route('estimasi'))->with('success','Berhasil Menghapus Data');
    }

    public function status($id)
    {
        $this->fill();
        $data = $this->getMenuAll();

        $notifikasis = new \App\notifikasi();
        $notifikasi = $notifikasis->where('id_estimasi','=',$id)->update(['read'=>1]);

        $estimasi = new \App\estimasi();
        $data['data_estimasi'] = $estimasi->where('id',$id)->get();

        return view('admin.manajemen_marketing.estimasi.status',$data);
    }

    public function statusUpdates(Request $request)
    {
        if ($request->status == 'Diterima') {
            $data_estimasis = DB::table('estimasis')
                                    ->select('estimasis.*')
                                    ->where('estimasis.id', $request->id)->get();

            $id_produk = $data_estimasis[0]->produk;
            $id_cabang = $data_estimasis[0]->cabang_id;
            $jumlah_barang_keluar = $data_estimasis[0]->kuantiti;
            $user = $data_estimasis[0]->nama_user;
            $produk = $data_estimasis[0]->produk;
            $cabang_id = $data_estimasis[0]->cabang_id;

            $eksisting_produk_data = DB::table('produk_cabangs')
                                            ->select('produk_cabangs.*')
                                            ->where('produk_cabangs.produk_id', $id_produk)
                                            ->where('produk_cabangs.cabang_id', $id_cabang)->get();

            $eksisting_stok = $eksisting_produk_data[0]->jumlah;

            if ($eksisting_stok > $jumlah_barang_keluar) {
                $estimasi = estimasi::find($request->id);
                $estimasi->status = $request->status;
                $estimasi->save();
                return redirect()->to(route('estimasi'))->with('success','Berhasil Mengubah Status');
            } else {
                return redirect()->to(route('estimasi'))->with('failed','Stok Tidak Mencukupi');
            }

        } else {
            $estimasi = estimasi::find($request->id);
            $estimasi->status = $request->status;
            $estimasi->save();
            return redirect()->to(route('estimasi'))->with('success','Berhasil Mengubah Status');
        }

    }

    public function getEstimasiDetails($id)
    {
        $estimasi = DB::table('estimasis')
                        ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'estimasis.coverage_area_id')
                        ->leftJoin('cabangs', 'cabangs.id', '=', 'estimasis.cabang_id')
                        ->leftJoin('distributors', 'distributors.id', '=', 'estimasis.distributor_id')
                        ->leftJoin('outlets', 'outlets.id', '=', 'estimasis.outlet')
                        ->leftJoin('produks', 'produks.id', '=', 'estimasis.produk')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                        ->select('estimasis.*', 'daftar_users.nama_user', 'produks.nama as nama_produk', 'outlets.nama_outlet', 'coverage_areas.nama_coverage_area', 'cabangs.nama_cabang', 'distributors.nama_distributor')
                        ->where('estimasis.id', $id)
                        ->whereNull('estimasis.deleted_at')->get();

        $bulan = date('F', strtotime($estimasi[0]->bulan));
        $estimasi[0]->bulan = $bulan;

        echo json_encode($estimasi);
    }
    public function export(Request $request)
    {
        $dm = session('dm_estimasi');
        $bulan = session('bulan_estimasi');
        $tahun = session('tahun_estimasi');
        $jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();
        if ($jabatan_user[0]->nama_jabatan=="DM") {
            return Excel::download(new EstimasiExport($jabatan_user,Auth::user()->id,$bulan,$tahun), 'estimasi.xlsx');
        } elseif($jabatan_user[0]->nama_jabatan=="Direktur" || $jabatan_user[0]->nama_jabatan=="Superuser") {
            return Excel::download(new EstimasiExport($jabatan_user,$dm,$bulan,$tahun), 'estimasi.xlsx');
        }
    }

    public function getCoverageAreaByUser($id)
    {
        $coverage_areas = "";

        if ($id != '0') {
            $coverage_areas = DB::table('user_coverage_areas')
                            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'user_coverage_areas.coverage_area_id')
                            ->select('coverage_areas.id', 'coverage_areas.nama_coverage_area')
                            ->where('user_coverage_areas.user_id', $id)->get();
        }

        echo json_encode($coverage_areas);
    }

    public function getCabangByArea($id)
    {
        $cabangs = "";

        if ($id != '0') {
            $cabangs = DB::table('cabangs')
                            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'cabangs.coverage_area_id')
                            ->select('cabangs.id', 'cabangs.nama_cabang')
                            ->where('cabangs.coverage_area_id', $id)
                            ->whereNull('cabangs.deleted_at')->get();
        }

        echo json_encode($cabangs);
    }

    public function getDistributorByCabang($id)
    {
        $distributors = "";

        if ($id !== '0') {
            $distributors = DB::table('produk_cabangs')
                                ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                                ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
                                ->select('distributors.id', 'distributors.nama_distributor')
                                ->groupBy('distributors.id', 'distributors.nama_distributor')
                                ->where('produk_cabangs.cabang_id', $id)->get();
        }

        echo json_encode($distributors);
    }

    public function getProdukByDist($id,$cabang_id)
    {
        $produks = "";
        if ($id !== '0') {
            $produks = DB::table('produk_cabangs')
                                ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                                ->select('produks.id', 'produks.nama as nama_produk', 'produks.harga')
                                ->where('produk_cabangs.cabang_id', $cabang_id)
                                ->where('produks.distributor_id', $id)->get();
        }
        // if ($id != '0') {
        //     $produks = DB::table('produks')
        //                     ->select('produks.id', 'produks.nama as nama_produk', 'produks.harga')
        //                     ->where('produks.distributor_id', $id)->whereNull('deleted_at')->get();
        // }

        echo json_encode($produks);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'notif'  => 'required',
            'status_update' => 'required'
        ]);

        for($i=0; $i < count($request->notif); $i++) {

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
        }

        return redirect()->to(route('estimasi'))->with('success','Berhasil Update Status');
    }
}
