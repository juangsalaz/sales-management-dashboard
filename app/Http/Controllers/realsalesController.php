<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\real_sales;
use App\outlet;
use App\produk;
use App\distributor;
use DB;
use Carbon\Carbon;
use App\Exports\RealSalesExport;
use Maatwebsite\Excel\Facades\Excel;

class realsalesController extends Controller
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
                        ->where('sub_menu1s.nama', 'Real Sales')
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

    public function countDistributorProduct($id,$dm = null, $awal=null, $akhir=null)
    {
        $query = DB::table('real_sales')
                                ->select('real_sales.*')
                                ->where('real_sales.distributor_id', $id)
                                ->where('status','Diterima')
                                ->whereNull('deleted_at');
        if ($dm!=null) {
            $query->where('real_sales.nama_user', $dm);
        }
        if ($awal != null and $akhir != null) {
            $awal = date('Y-m-d', strtotime($awal));
            $akhir = date('Y-m-d', strtotime($akhir));
            $query->whereBetween('real_sales.bulan', [$awal, $akhir]);
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

        //Diskon Rata-Rata by Distributor

        $diskon_rata_by_dist = 0;
        $sisa = 100;
        if ($total_diskon_value > 0 && $total_value_gross > 0) {

            $diskon_rata_by_dist = ($total_diskon_value/$total_value_gross)*100;
            if ($diskon_rata_by_dist >= 100) {
                $diskon_rata_by_dist = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $diskon_rata_by_dist;
            }
        }

        // $array_data['diskon_rata_by_dist']['nilai'] = array(round($diskon_rata_by_dist, 0), $sisa);

        $rata_diskon = round($diskon_rata_by_dist, 0);
        // if ($total_data != 0) {
        //     $rata_diskon = $total_diskon/$total_data;
        // }

        $array_data = array('total_product'=>count($array_product), 'total_value_nett'=>$total_value_nett, 'total_value_gross'=>$total_value_gross, 'rata_diskon'=>$rata_diskon, 'total_diskon_value'=>$total_diskon_value);

        return $array_data;
    }

    public function getMenuAll($dm = null, $bulan=null, $tahun=null){
        $awal=null;
        $akhir=null;
        $jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();
        if ($jabatan_user[0]->nama_jabatan=="DM") {
            $query = DB::table('real_sales')
                            ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                            ->leftJoin('outlets', 'outlets.id', '=', 'real_sales.outlet')
                            ->leftJoin('produks', 'produks.id', '=', 'real_sales.produk')
                            ->select('real_sales.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                            ->whereNull('real_sales.deleted_at')
                            ->where('real_sales.nama_user',Auth::user()->id)
                            ->orderBy('real_sales.created_at','desc');
            if ($bulan != null and $tahun != null) {
                $awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-1'));
                $akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-1'));
                $query->whereBetween('real_sales.bulan', [$awal, $akhir]);
            }
            $real_sales = $query->get();
        } elseif($jabatan_user[0]->nama_jabatan=="Direktur" || $jabatan_user[0]->nama_jabatan=="Superuser"){
                $query = DB::table('real_sales')
                                ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                                ->leftJoin('outlets', 'outlets.id', '=', 'real_sales.outlet')
                                ->leftJoin('produks', 'produks.id', '=', 'real_sales.produk')
                                ->select('real_sales.*', 'outlets.nama_outlet', 'daftar_users.nama_user', 'produks.nama as nama_produk')
                                ->whereNull('real_sales.deleted_at')
                                ->orderBy('real_sales.created_at','desc');
                if($dm != null){
                    $query->where('real_sales.nama_user',$dm);
                }
                if ($bulan != null and $tahun != null) {
                    $awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-1'));
                    $akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-1'));
                    $query->whereBetween('real_sales.bulan', [$awal, $akhir]);
                }
                $real_sales = $query->get();
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

        $data = array('jabatan_pengguna'=>$jabatan_user[0]->nama_jabatan,'pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'real_sales'=>$real_sales, 'total_rni'=>$total_rni_product, 'total_igm'=>$total_igm_product, 'total_mbs'=>$total_mbs_product,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count, 'data_dm'=>$data_dm);

        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(Request $request){
        session(['dm_real_sales' => $request->user_dm,'bulan_real_sales'=>$request->bulan,'tahun_real_sales'=>$request->tahun]);
        $this->fill();
        if ($request->user_dm==null and $request->bulan==null and $request->tahun==null) {
            $data = $this->getMenuAll();
        } else {
            $data = $this->getMenuAll($request->user_dm, $request->bulan, $request->tahun);
        }
        return view('admin.manajemen_marketing.real_sales.index',$data);
    }
    public function add(){
        $this->fill();
        $data = $this->getMenuAll();

        $outlets = outlet::all();
        $data['outlets'] = $outlets;

        $produks = produk::all();
        $data['produks'] = $produks;

        return view('admin.manajemen_marketing.real_sales.add',$data);
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

        $real_sales = new \App\real_sales();

        $bulan = date('Y-m-d', strtotime($request->tahun.'-'.$request->bulan.'-1'));
        $real_sales->insert(['bulan'=>$bulan,'tahun'=>$request->tahun, 'nama_user'=>$request->user, 'outlet'=>$request->outlet, 'produk'=>$request->produk, 'harga_produk'=>$harga, 'kuantiti'=>$kuantiti, 'value_gross'=>$value_gross, 'diskon'=>$diskon, 'diskon_value'=>$diskon_value, 'value_nett'=>$value_net, 'status'=>$request->status,'created_at'=>$this->now(),'updated_at'=>$this->now(), 'coverage_area_id'=>$request->coverage_area, 'cabang_id'=>$request->cabang, 'distributor_id'=>$request->distributor]);

        $last = DB::table('real_sales')->where('nama_user','=',$request->user)->orderBy('created_at','desc')->first();
        $notifikasi = new \App\notifikasi();
        $notifikasi->insert(['id_user'=>$request->user,'id_real_sales'=>$last->id,'read'=>0,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);

        return redirect()->to(route('realsales'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();

        $outlets = outlet::all();
        $data['outlets'] = $outlets;

        $produks = produk::all();
        $data['produks'] = $produks;

        $real_sales = new \App\real_sales();
        $data_realsales = $real_sales->where('id',$id)->get();
        $data['data_real_sales'] = $data_realsales;

        $coverage_areas = DB::table('user_coverage_areas')
                            ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'user_coverage_areas.coverage_area_id')
                            ->select('coverage_areas.id', 'coverage_areas.nama_coverage_area')
                            ->where('user_coverage_areas.user_id', $data_realsales[0]->nama_user)->get();
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

        return view('admin.manajemen_marketing.real_sales.edit',$data);
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
        ]);

        $diskon = str_replace(",",".",$request->diskon);
        $kuantiti = str_replace(".","",$request->kuantiti);
        $harga = str_replace(".","",$request->harga_produk);
        $value_gross = str_replace(".","",$request->value_gross);
        $diskon_value = str_replace(".","",$request->diskon_value);
        $value_net = str_replace(".","",$request->value_nett);

        $bulan = date('Y-m-d', strtotime($request->tahun.'-'.$request->bulan.'-1'));

        $real_sales = real_sales::find($request->id);
        $real_sales->bulan = $bulan;
        $real_sales->tahun = $request->tahun;
        $real_sales->outlet = $request->outlet;
        $real_sales->produk = $request->produk;
        $real_sales->harga_produk = $harga;
        $real_sales->kuantiti = $kuantiti;
        $real_sales->value_gross = $value_gross;
        $real_sales->diskon = $diskon;
        $real_sales->diskon_value = $diskon_value;
        $real_sales->value_nett = $value_net;
        $real_sales->status = $request->status;
        $real_sales->save();

        return redirect()->to(route('realsales'))->with('success','Berhasil Menambahkan Data');
    }
    public function delete(Request $request){
        $real_sales = real_sales::find($request->id);
        $real_sales->delete();
        return redirect()->to(route('realsales'))->with('success','Berhasil Menghapus Data');
    }

    public function status($id)
    {
        $this->fill();
        $data = $this->getMenuAll();

        $notifikasis = new \App\notifikasi();
        $notifikasi = $notifikasis->where('id_real_sales','=',$id)->update(['read'=>1]);

        $real_sales = new \App\real_sales();
        $data['data_real_sales'] = $real_sales->where('id',$id)->get();

        return view('admin.manajemen_marketing.real_sales.status',$data);
    }

    public function statusUpdate(Request $request)
    {
        $request->validate([
            'status'  => 'required'
        ]);

        if ($request->status == 'Diterima') {
            $data_real_sales = DB::table('real_sales')
                                    ->select('real_sales.*')
                                    ->where('real_sales.id', $request->id)->get();

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

                $real_sales = real_sales::find($request->id);
                $real_sales->status = $request->status;
                $real_sales->save();
                return redirect()->to(route('realsales'))->with('success','Berhasil Mengubah Status');
            } else {
                return redirect()->to(route('realsales'))->with('failed','Stok Tidak Mencukupi');
            }

        } else {
            $real_sales = real_sales::find($request->id);
            $real_sales->status = $request->status;
            $real_sales->save();
            return redirect()->to(route('realsales'))->with('success','Berhasil Mengubah Status');
        }

    }

    public function getRealSalesDetails($id)
    {
        $realsales = DB::table('real_sales')
                        ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'real_sales.coverage_area_id')
                        ->leftJoin('cabangs', 'cabangs.id', '=', 'real_sales.cabang_id')
                        ->leftJoin('distributors', 'distributors.id', '=', 'real_sales.distributor_id')
                        ->leftJoin('outlets', 'outlets.id', '=', 'real_sales.outlet')
                        ->leftJoin('produks', 'produks.id', '=', 'real_sales.produk')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                        ->select('real_sales.*', 'daftar_users.nama_user', 'produks.nama as nama_produk', 'outlets.nama_outlet', 'coverage_areas.nama_coverage_area', 'cabangs.nama_cabang', 'distributors.nama_distributor')
                        ->where('real_sales.id', $id)
                        ->whereNull('real_sales.deleted_at')->get();

        $bulan = date('F', strtotime($realsales[0]->bulan));
        $realsales[0]->bulan = $bulan;

        echo json_encode($realsales);
    }
    public function export()
    {
        $dm = session('dm_real_sales');
        $bulan = session('bulan_real_sales');
        $tahun = session('tahun_real_sales');
        $jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();
        if ($jabatan_user[0]->nama_jabatan=="DM") {
            return Excel::download(new RealSalesExport($jabatan_user,Auth::user()->id,$bulan,$tahun), 'real-sales.xlsx');
        } elseif($jabatan_user[0]->nama_jabatan=="Direktur" || $jabatan_user[0]->nama_jabatan=="Superuser") {
            return Excel::download(new RealSalesExport($jabatan_user,$dm,$bulan,$tahun), 'real-sales.xlsx');
        }
    }

    public function getCoverageAreaByUser($id)
    {
        $coverage_areas = "";

        if ($id != 0) {
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

        if ($id != 0) {
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

        if ($id !== 0) {
            $distributors = DB::table('produk_cabangs')
                                ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                                ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
                                ->select('distributors.id', 'distributors.nama_distributor')
                                ->where('produk_cabangs.cabang_id', $id)->get();
        }

        echo json_encode($distributors);
    }

    public function getProdukByDist($id)
    {
        $produks = "";

        if ($id != 0) {
            $produks = DB::table('produks')
                            ->select('produks.id', 'produks.nama as nama_produk', 'produks.harga')
                            ->where('produks.distributor_id', $id)->get();
        }

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
                ->where('notifikasis.id_estimasi', $request->notif[$i])
                ->update(array('read' => 1));
        }

        return redirect()->to(route('realsales'))->with('success','Berhasil Update Status');
    }
}
