<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\daftar_user;
use App\running_text;
use App\distributor;
use App\estimasi;
use Backup;

class DashboardController extends Controller
{
    protected $daftar_menu;
    protected $daftar_sub_menu;
    protected $jabatan;
    public function getJabatan(){
        return Auth::user()->jabatan;
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

    public function countDistributorProduct($id)
    {
        $array_product = DB::table('real_sales')
                            ->select('real_sales.*')
                            ->where('real_sales.distributor_id', $id)
                            ->whereNull('deleted_at')->get();

        return count($array_product);
    }

    public function index(){
        $this->jabatan = $this->getJabatan();
        $this->getMenuJabatan();
        $this->getSubMenu();

        $data_distributors = DB::table('distributors')
                                ->select('*')->get();

        $dist_rni = distributor::where('nama_distributor', 'RNI')->first();
        $total_rni_product = $this->countDistributorProduct($dist_rni->id);

        $dist_mbs = distributor::where('nama_distributor', 'MBS')->first();
        $total_mbs_product = $this->countDistributorProduct($dist_mbs->id);

        $dist_igm = distributor::where('nama_distributor', 'IGM')->first();
        $total_igm_product = $this->countDistributorProduct($dist_igm->id);

        $running_teks = running_text::first();

        $jabatan_user = $this->list_jabatan->select('nama_jabatan')->where('id','=',Auth::user()->jabatan)->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();

        return view('admin.dashboard',$data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu, 'running_teks'=>$running_teks, 'notif'=>$this->notif,'pengingat_count'=>$pengingat_count, 'total_rni_product'=>$total_rni_product, 'total_mbs_product'=>$total_mbs_product, 'total_igm_product'=>$total_igm_product, 'distributors'=>$data_distributors));
    }

    public function realSalesData()
    {
        $real_sales = DB::table('real_sales')
                     ->select(DB::raw('count(*) as real_sales_count, bulan'))
                     ->where('deleted_at', '=', null)
                     ->groupBy('bulan')
                     ->orderBy('bulan', 'desc')
                    //  ->where('status','Diterima')
                     ->get();

        $array_data = array();
        foreach($real_sales as $sales) {
            $array_data['bulan'][] = date('F', strtotime($sales->bulan));
            $array_data['jumlah'][] = $sales->real_sales_count;
        }

        echo json_encode($array_data);
    }

    public function stokProdukData()
    {
        $stok_produk = DB::table('produk_cabangs')
                        ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                        ->select('produk_id', 'produks.nama', DB::raw('SUM (produk_cabangs.jumlah) as jumlah'))
                        ->groupBy('produk_id', 'produks.nama')
                        ->get();

        $array_data = array();
        foreach($stok_produk as $produk) {
            $array_data['produk'][] = $produk->nama;
            $array_data['jumlah'][] = $produk->jumlah;
        }

        echo json_encode($array_data);
    }

    public function stokProdukDataByDistributor($id)
    {
        $stok_produk = DB::table('produk_cabangs')
                        ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                        ->select('produk_id', 'produks.nama', DB::raw('SUM (produk_cabangs.jumlah) as jumlah'))
                        ->where('produks.distributor_id', $id)
                        ->groupBy('produk_id', 'produks.nama')
                        ->get();

        $array_data = array();
        foreach($stok_produk as $produk) {
            $array_data['produk'][] = $produk->nama;
            $array_data['jumlah'][] = $produk->jumlah;
        }

        echo json_encode($array_data);
    }

    public function realSalesByEstimasi()
    {
        $realsales = DB::table('real_sales')
                     ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                     ->select(DB::raw('count(*) as user_count, real_sales.nama_user, daftar_users.nama_user as nama, daftar_users.id as user_id'))
                     ->where('real_sales.status', 'Diterima')
                     ->groupBy('real_sales.nama_user', 'daftar_users.nama_user', 'daftar_users.id')
                     ->get()->toArray();;

        if(!empty($realsales)) {
            $data_realsales[] = max($realsales);
        }

        $user_id = $data_realsales[0]->user_id;
        $nama_user = $data_realsales[0]->nama;

        $data_realsales = array();

        $realsales = DB::table('real_sales')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                        ->where('real_sales.nama_user', $user_id)
                        ->where('real_sales.status', 'Diterima')
                        ->whereNull('real_sales.deleted_at')
                        ->select('real_sales.*', 'real_sales.nama_user as user_id', 'daftar_users.nama_user')
                        ->orderBy('real_sales.value_gross', 'desc')->get();
                            
        if($realsales->isNotEmpty()) {
            $data_realsales[] = $realsales;
        }

        $total_value_nett = 0;
        $total_value_gross = 0;
        $total_diskon = 0;
        $total_diskon_value = 0;
        $total_data = 0;
        if($realsales->isNotEmpty()) {
            foreach ($data_realsales[0] as $row) {
                $total_value_nett = $total_value_nett+$row->value_nett;
                $total_value_gross = $total_value_gross+$row->value_gross;
                $total_diskon = $total_diskon+$row->diskon;
                $total_diskon_value = $total_diskon_value+$row->diskon_value;
                $total_data++;
            }
        }

        $data_estimasis = array();

        $estimasi = DB::table('estimasis')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                        ->where('estimasis.nama_user', $user_id)
                        ->where('estimasis.status', 'Diterima')
                        ->whereNull('estimasis.deleted_at')
                        ->select('estimasis.*', 'estimasis.nama_user as user_id', 'daftar_users.nama_user')
                        ->orderBy('estimasis.value_gross', 'desc')
                        ->get();
                
        if($estimasi->isNotEmpty()) {
            $data_estimasis[] = $estimasi;
        }

        $total_value_nett_estimasi = 0;
        $total_value_gross_estimasi = 0;
        $total_diskon_estimasi = 0;
        $total_diskon_estimasi_value = 0;
        $tota_data_estimasi = 0;
        if($estimasi->isNotEmpty()) {
            foreach ($data_estimasis[0] as $row) {
                $total_value_nett_estimasi = $total_value_nett_estimasi+$row->value_nett;
                $total_value_gross_estimasi = $total_value_gross_estimasi+$row->value_gross;
                $total_diskon_estimasi = $total_diskon_estimasi+$row->diskon;
                $total_diskon_estimasi_value = $total_diskon_estimasi_value+$row->diskon_value;
                $tota_data_estimasi++;
            }
        }

        //real sales by estimasi
        $realsales_by_estimasi = 0;
        $sisa = 100;
        if ($total_value_gross > 0 && $total_value_gross_estimasi > 0) {
            $realsales_by_estimasi = ($total_value_gross/$total_value_gross_estimasi)*100;
            if ($realsales_by_estimasi >= 100) {
                $realsales_by_estimasi = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $realsales_by_estimasi;
            }
        }

        $array_real_sales_by_estimasi['nilai'] = array(round($realsales_by_estimasi, 0), $sisa);
        $array_real_sales_by_estimasi['user'] = $nama_user;

        echo json_encode($array_real_sales_by_estimasi);
    }

    public function backup()
    {
        Backup::export('backup-db');

        $filename = '../storage/backup-db.pgsql.gz';

        if (file_exists($filename)) {
            $headers = [
                'Content-Type' => 'application/pdf',
             ];
  
            return response()->download($filename, 'backup-db.pgsql.gz', $headers)->deleteFileAfterSend();

        }
    }
}
