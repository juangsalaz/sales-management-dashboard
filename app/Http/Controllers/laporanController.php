<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\daftar_user;
use App\distributor;
use Carbon\Carbon;

class laporanController extends Controller
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
                        ->where('sub_menu1s.nama', 'Daftar Cabang')
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
        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();
        $data = array('pengguna' => Auth::user()->nama_user,'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        $this->$now = Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();

        $users = daftar_user::all();
        $data['users'] = $users;

        $distributors = distributor::all();
        $data['distributors'] = $distributors;

        $id_jabatan = Auth::user()->jabatan;
        $nama_jabatan = DB::table('jabatans')->select('nama_jabatan')->where('id', $id_jabatan)->get();

        $data['nama_jabatan'] = $nama_jabatan[0]->nama_jabatan;
        $data['id_user'] = Auth::user()->id;

        return view('admin.laporan.index', $data);
    }

    public function grafikDataAllUsers($id_distributor, $start_date, $end_date)
    {
        
        $start="";
        $end="";
        
        if ($start_date != '' && $end_date != '') {
            $start = date('Y-m-d', strtotime($start_date));
            $end = date('Y-m-d', strtotime($end_date));
        }

        $data_realsales = array();

        if ($start_date != '' and $end_date != '') {
            $realsales = DB::table('real_sales')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                        ->where('real_sales.distributor_id', $id_distributor)
                        ->where('real_sales.status', 'Diterima')
                        ->whereNull('real_sales.deleted_at')
                        ->whereBetween('real_sales.bulan', [$start, $end])
                        ->select('real_sales.*', 'real_sales.nama_user as user_id', 'daftar_users.nama_user')
                        ->orderBy('real_sales.value_gross', 'desc')->get();

            if($realsales->isNotEmpty()) {
                $data_realsales[] = $realsales;
            }
        } else {
            $realsales = DB::table('real_sales')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                        ->where('real_sales.distributor_id', $id_distributor)
                        ->where('real_sales.status', 'Diterima')
                        ->whereNull('real_sales.deleted_at')
                        ->select('real_sales.*', 'real_sales.nama_user as user_id', 'daftar_users.nama_user')
                        ->orderBy('real_sales.value_gross', 'desc')->get();

            if($realsales->isNotEmpty()) {
                $data_realsales[] = $realsales;
            }
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

        if ($start_date != '' and $end_date != '') {
            $estimasi = DB::table('estimasis')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                        ->where('estimasis.distributor_id', $id_distributor)
                        ->where('estimasis.status', 'Diterima')
                        ->whereNull('estimasis.deleted_at')
                        ->whereBetween('estimasis.bulan', [$start, $end])
                        ->select('estimasis.*', 'estimasis.nama_user as user_id', 'daftar_users.nama_user')
                        ->orderBy('estimasis.value_gross', 'desc')
                        ->get();

            if($estimasi->isNotEmpty()) {
                $data_estimasis[] = $estimasi;
            }
        } else {
            $estimasi = DB::table('estimasis')
                        ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                        ->where('estimasis.distributor_id', $id_distributor)
                        ->where('estimasis.status', 'Diterima')
                        ->whereNull('estimasis.deleted_at')
                        ->select('estimasis.*', 'estimasis.nama_user as user_id', 'daftar_users.nama_user')
                        ->orderBy('estimasis.value_gross', 'desc')
                        ->get();

            if($estimasi->isNotEmpty()) {
                $data_estimasis[] = $estimasi;
            }
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

        if ($start_date != '' and $end_date != '') {
            $total_nilai_target = DB::table('target_distributors')
                                        ->whereNull('target_distributors.deleted_at')
                                        ->where('target_distributors.distributor', $id_distributor)
                                        ->whereBetween('target_distributors.bulan', [$start, $end])
                                        ->sum('target');

            $total_data_produk = DB::table('data_stoks')
                                        ->whereNull('data_stoks.deleted_at')
                                        ->whereBetween('data_stoks.created_at', [$start, $end])
                                        ->sum('kuantiti');

            $total_dead_stok = DB::table('death_stoks')
                                        ->whereNull('death_stoks.deleted_at')
                                        ->whereBetween('death_stoks.created_at', [$start, $end])
                                        ->count();

            $total_real_sales_produk = DB::table('real_sales')
                                        ->whereNull('real_sales.deleted_at')
                                        ->whereBetween('real_sales.bulan', [$start, $end])
                                        ->sum('kuantiti');
        } else {
            $total_nilai_target = DB::table('target_distributors')
                                        ->whereNull('target_distributors.deleted_at')
                                        ->where('target_distributors.distributor', $id_distributor)
                                        ->sum('target');

            $total_data_produk = DB::table('data_stoks')
                                        ->whereNull('data_stoks.deleted_at')
                                        ->sum('kuantiti');

            $total_dead_stok = DB::table('death_stoks')
                                        ->whereNull('death_stoks.deleted_at')
                                        ->count();

            $total_real_sales_produk = DB::table('real_sales')
                                        ->whereNull('real_sales.deleted_at')
                                        ->sum('kuantiti');
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

        $array_data['realsales_by_estimasi']['nilai'] = array(round($realsales_by_estimasi, 0), $sisa);

        //Real Sales by Target
        $realsales_by_target = 0;
        $sisa = 100;
        if ($total_value_gross > 0 && $total_nilai_target > 0) {
            $realsales_by_target = ($total_value_gross/$total_nilai_target)*100;
            if ($realsales_by_target >= 100) {
                $realsales_by_target = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $realsales_by_target;
            }
        }

        $array_data['realsales_by_target']['nilai'] = array(round($realsales_by_target, 0), $sisa);

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

        $array_data['diskon_rata_by_dist']['nilai'] = array(round($diskon_rata_by_dist, 0), $sisa);

        //Total Real Sales by Total Estimasi Diskon Value
        $realsales_diskon_value_by_estimasi_diskon_value = 0;
        $sisa = 100;
        if ($total_diskon_value > 0 && $total_diskon_estimasi_value > 0) {
            $realsales_diskon_value_by_estimasi_diskon_value = ($total_diskon_value/$total_diskon_estimasi_value)*100;
            if ($realsales_diskon_value_by_estimasi_diskon_value >= 100) {
                $realsales_diskon_value_by_estimasi_diskon_value = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $realsales_diskon_value_by_estimasi_diskon_value;
            }
        }

        $array_data['realsales_diskon_value_by_estimasi_diskon_value']['nilai'] = array(round($realsales_diskon_value_by_estimasi_diskon_value, 0), $sisa);

        //Total Real Sales by Total Estimasi Value Nett
        $realsales_value_nett_by_estimasi_value_nett = 0;
        $sisa = 100;
        if ($total_value_nett > 0 && $total_value_nett_estimasi > 0) {
            $realsales_value_nett_by_estimasi_value_nett = ($total_value_nett/$total_value_nett_estimasi)*100;
            if ($realsales_value_nett_by_estimasi_value_nett >= 100) {
                $realsales_value_nett_by_estimasi_value_nett = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $realsales_value_nett_by_estimasi_value_nett;
            }
        }

        $array_data['realsales_value_nett_by_estimasi_value_nett']['nilai'] = array(round($realsales_value_nett_by_estimasi_value_nett, 0), $sisa);

        //Total Real Sales by Total Estimasi Diskon Rata-Rata
        $diskon_rata_real_by_estimasi = 0;
        $sisa = 100;
        if ($total_diskon > 0 && $tota_data_estimasi > 0) {
            $total_diskon_rata = $total_diskon/$total_data;

            $total_diskon_rata_estimasi = $total_diskon_estimasi/$tota_data_estimasi;

            $diskon_rata_real_by_estimasi = ($total_diskon_rata/$total_diskon_rata_estimasi)*100;
            if ($diskon_rata_real_by_estimasi >= 100) {
                $diskon_rata_real_by_estimasi = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $diskon_rata_real_by_estimasi;
            }
        }

        $array_data['diskon_rata_real_by_estimasi']['nilai'] = array(round($diskon_rata_real_by_estimasi, 0), $sisa);

        //Total Real Sales Diskon Value by Target Distributor
        $diskon_value_by_dist = 0;
        $sisa = 100;
        if ($total_diskon_value > 0 && $total_nilai_target > 0) {
            $diskon_value_by_dist = ($total_diskon_value/$total_nilai_target)*100;
            if ($diskon_value_by_dist >= 100) {
                $diskon_value_by_dist = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $diskon_value_by_dist;
            }
        }

        $array_data['diskon_value_by_dist']['nilai'] = array(round($diskon_value_by_dist, 0), $sisa);

        //Total Real Sales Value Nett by Target Distributor
        $realsales_value_nett_by_dist = 0;
        $sisa = 100;
        if ($total_value_nett > 0 && $total_nilai_target > 0) {
            $realsales_value_nett_by_dist = ($total_value_nett/$total_nilai_target)*100;
            if ($realsales_value_nett_by_dist >= 100) {
                $realsales_value_nett_by_dist = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $realsales_value_nett_by_dist;
            }
        }

        $array_data['realsales_value_nett_by_dist']['nilai'] = array(round($realsales_value_nett_by_dist, 0), $sisa);

        //Total Real Sales Diskon Rata-Rata by Target Distributor
        $diskon_rata_by_target = 0;
        $sisa = 100;
        if ($total_diskon > 0 && $total_nilai_target > 0) {
            $total_diskon_rata = $total_diskon/$total_data;

            $diskon_rata_by_target = ($total_diskon_rata/$total_nilai_target)*100;
            if ($diskon_rata_by_target >= 100) {
                $diskon_rata_by_target = 100;
                $sisa = 0;
            } else {
                $sisa = 100 - $diskon_rata_by_target;
            }
        }

        $array_data['diskon_rata_by_target']['nilai'] = array(round($diskon_rata_by_target, 0), $sisa);

        echo json_encode($array_data);
    }

    public function grafikData(Request $request)
    {
        $request->validate([
            'user_id'  => 'required',
            'distributor'   => 'required'
        ]);

        if ($request->user_id == 'all') {
            $this->grafikDataAllUsers($request->distributor, $request->start, $request->end);
        } else {
            $id_distributor = $request->distributor;
            $id_user = $request->user_id;
            
            $start="";
            $end="";
            $start1="";
            $end1="";
            
            if ($request->start != '' && $request->end != '') {
                $start = date('Y-m-d', strtotime($request->start));
                $end = date('Y-m-d', strtotime($request->end));
            }

            $data_realsales = array();

            if ($request->start != '' and $request->end != '') {
                $realsales = DB::table('real_sales')
                            ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                            ->where('real_sales.distributor_id', $id_distributor)
                            ->where('real_sales.nama_user', $id_user)
                            ->where('real_sales.status', 'Diterima')
                            ->whereNull('real_sales.deleted_at')
                            ->whereBetween('real_sales.bulan', [$start, $end])
                            ->select('real_sales.*', 'real_sales.nama_user as user_id', 'daftar_users.nama_user')
                            ->orderBy('real_sales.value_gross', 'desc')->get();
                
                if($realsales->isNotEmpty()) {
                    $data_realsales[] = $realsales;
                }
            } else {
                $realsales = DB::table('real_sales')
                            ->leftJoin('daftar_users', 'daftar_users.id', '=', 'real_sales.nama_user')
                            ->where('real_sales.distributor_id', $id_distributor)
                            ->where('real_sales.nama_user', $id_user)
                            ->where('real_sales.status', 'Diterima')
                            ->whereNull('real_sales.deleted_at')
                            ->select('real_sales.*', 'real_sales.nama_user as user_id', 'daftar_users.nama_user')
                            ->orderBy('real_sales.value_gross', 'desc')->get();
                
                if($realsales->isNotEmpty()) {
                    $data_realsales[] = $realsales;
                }
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

            if ($request->start != '' and $request->end != '') {
                $estimasi = DB::table('estimasis')
                            ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                            ->where('estimasis.distributor_id', $id_distributor)
                            ->where('estimasis.nama_user', $id_user)
                            ->where('estimasis.status', 'Diterima')
                            ->whereNull('estimasis.deleted_at')
                            ->whereBetween('estimasis.bulan', [$start, $end])
                            ->select('estimasis.*', 'estimasis.nama_user as user_id', 'daftar_users.nama_user')
                            ->orderBy('estimasis.value_gross', 'desc')
                            ->get();
                
                if($estimasi->isNotEmpty()) {
                    $data_estimasis[] = $estimasi;
                }
            } else {
                $estimasi = DB::table('estimasis')
                            ->leftJoin('daftar_users', 'daftar_users.id', '=', 'estimasis.nama_user')
                            ->where('estimasis.distributor_id', $id_distributor)
                            ->where('estimasis.nama_user', $id_user)
                            ->where('estimasis.status', 'Diterima')
                            ->whereNull('estimasis.deleted_at')
                            ->select('estimasis.*', 'estimasis.nama_user as user_id', 'daftar_users.nama_user')
                            ->orderBy('estimasis.value_gross', 'desc')
                            ->get();
                
                if($estimasi->isNotEmpty()) {
                    $data_estimasis[] = $estimasi;
                }
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

            if ($request->start != '' and $request->end != '') {
                $total_nilai_target = DB::table('target_distributors')
                                            ->where('user', $id_user)
                                            ->where('target_distributors.distributor', $id_distributor)
                                            ->whereNull('target_distributors.deleted_at')
                                            ->whereBetween('target_distributors.bulan', [$start, $end])
                                            ->sum('target');

                $total_data_produk = DB::table('data_stoks')
                                            ->where('user', $id_user)
                                            ->whereNull('data_stoks.deleted_at')
                                            ->whereBetween('data_stoks.created_at', [$start, $end])
                                            ->sum('kuantiti');

                $total_dead_stok = DB::table('death_stoks')
                                            ->where('user', $id_user)
                                            ->whereNull('death_stoks.deleted_at')
                                            ->whereBetween('death_stoks.created_at', [$start, $end])
                                            ->count();

                $total_real_sales_produk = DB::table('real_sales')
                                            ->where('nama_user', $id_user)
                                            ->whereNull('real_sales.deleted_at')
                                            ->whereBetween('real_sales.bulan', [$start, $end])
                                            ->sum('kuantiti');
            } else {
                $total_nilai_target = DB::table('target_distributors')
                                            ->where('user', $id_user)
                                            ->where('target_distributors.distributor', $id_distributor)
                                            ->whereNull('target_distributors.deleted_at')
                                            ->sum('target');

                $total_data_produk = DB::table('data_stoks')
                                            ->where('user', $id_user)
                                            ->whereNull('data_stoks.deleted_at')
                                            ->sum('kuantiti');

                $total_dead_stok = DB::table('death_stoks')
                                            ->where('user', $id_user)
                                            ->whereNull('death_stoks.deleted_at')
                                            ->count();

                $total_real_sales_produk = DB::table('real_sales')
                                            ->where('nama_user', $id_user)
                                            ->whereNull('real_sales.deleted_at')
                                            ->sum('kuantiti');
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

            $array_data['realsales_by_estimasi']['nilai'] = array(round($realsales_by_estimasi, 0), $sisa);

            //Real Sales by Target
            $realsales_by_target = 0;
            $sisa = 100;
            if ($total_value_gross > 0 && $total_nilai_target > 0) {
                $realsales_by_target = ($total_value_gross/$total_nilai_target)*100;
                if ($realsales_by_target >= 100) {
                    $realsales_by_target = 100;
                    $sisa = 0;
                } else {
                    $sisa = 100 - $realsales_by_target;
                }
            }

            $array_data['realsales_by_target']['nilai'] = array(round($realsales_by_target, 0), $sisa);

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

            $array_data['diskon_rata_by_dist']['nilai'] = array(round($diskon_rata_by_dist, 0), $sisa);

            //Total Real Sales by Total Estimasi Diskon Value
            $realsales_diskon_value_by_estimasi_diskon_value = 0;
            $sisa = 100;
            if ($total_diskon_value > 0 && $total_diskon_estimasi_value > 0) {
                $realsales_diskon_value_by_estimasi_diskon_value = ($total_diskon_value/$total_diskon_estimasi_value)*100;
                if ($realsales_diskon_value_by_estimasi_diskon_value >= 100) {
                    $realsales_diskon_value_by_estimasi_diskon_value = 100;
                    $sisa = 0;
                } else {
                    $sisa = 100 - $realsales_diskon_value_by_estimasi_diskon_value;
                }
            }

            $array_data['realsales_diskon_value_by_estimasi_diskon_value']['nilai'] = array(round($realsales_diskon_value_by_estimasi_diskon_value, 0), $sisa);

            //Total Real Sales by Total Estimasi Value Nett
            $realsales_value_nett_by_estimasi_value_nett = 0;
            $sisa = 100;
            if ($total_value_nett > 0 && $total_value_nett_estimasi > 0) {
                $realsales_value_nett_by_estimasi_value_nett = ($total_value_nett/$total_value_nett_estimasi)*100;
                if ($realsales_value_nett_by_estimasi_value_nett >= 100) {
                    $realsales_value_nett_by_estimasi_value_nett = 100;
                    $sisa = 0;
                } else {
                    $sisa = 100 - $realsales_value_nett_by_estimasi_value_nett;
                }
            }

            $array_data['realsales_value_nett_by_estimasi_value_nett']['nilai'] = array(round($realsales_value_nett_by_estimasi_value_nett, 0), $sisa);

            echo json_encode($array_data);
        }
    }
}
