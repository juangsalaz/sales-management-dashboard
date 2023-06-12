<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\produk;
use DB;
use Carbon\Carbon;
use App\Exports\ProdukExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class DaftarProdukController extends Controller
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
                        ->where('sub_menu1s.nama', 'Daftar Produk dan Harga')
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
        $data_produk = DB::table('produks')
                        ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
                        ->select('produks.*', 'distributors.nama_distributor')
                        ->get();

        $pengingat = new \App\pengingat();
        $data_pengingat_now = $pengingat->where('user', Auth::user()->id)->where('tanggal','=',date("Y-m-d"))->where('jam','>',date("H:i:s"))->get();
        $pengingat_count = $data_pengingat_now->count();
        $data = array('pengguna' => Auth::user()->nama_user, 'jabatan_user'=>$jabatan_user[0]['nama_jabatan'], 'data_menu' => $this->daftar_menu ,'data_sub_menu'=>$this->daftar_sub_menu,'data_sub_menu2'=>$this->daftar_sub_menu2,'produks'=>$data_produk,'notif'=>$this->notif,'pengingat_count'=>$pengingat_count);
        return $data;
    }
    public function now(){
        return Carbon::now()->toDateTimeString();
    }
    public function index(){
        $this->fill();
        $data = $this->getMenuAll();
        return view('admin.manajemen_produk.daftar_produk',$data);
    }
    public function tambah(){
        $this->fill();
        $data = $this->getMenuAll();

        $distributors = DB::table('distributors')
                            ->select('*')
                            ->get();
        $data['distributors'] = $distributors;

        $coverage_areas = DB::table('coverage_areas')
                            ->select('*')
                            ->get();
        $data['coverage_areas'] = $coverage_areas;

        return view('admin.manajemen_produk.daftar_produk_add',$data);
    }
    public function store(Request $request){

        $request->validate([
            'nama'  => 'required',
            'kode'   => 'required',
            'harga' => 'required',
            'distributor' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'coverage_areas.*' => 'required',
            'cabangs.*' => 'required',
            'jumlah.*' => 'required',
        ]);

        $harga = str_replace(".","",$request->harga);

        $fileName = '';
        if ($request->has('gambar')) {
            $image = $request->file('gambar');
            $fileName = date('YmdHmi').'-'.$image->getClientOriginalName();

            $image->move(public_path('images/produks'), $fileName);
        }

        $produk= new \App\produk();
        $produk->insert([]);

        $produk_id = DB::table('produks')->insertGetId(
            array('id' => Str::uuid(), 'nama'=>$request['nama'],'kode'=>$request['kode'],'golongan'=>$request['golongan'],'harga'=>$harga, 'gambar'=>$fileName, 'distributor_id'=>$request->distributor, 'created_at'=> $this->now(),'updated_at'=> $this->now())
        );

        for ($i=0; $i < count($request->coverage_areas); $i++) {
            DB::table('produk_cabangs')->insert(
                array('id' => Str::uuid(), 'produk_id' => $produk_id, 'coverage_area_id'=>$request->coverage_areas[$i], 'cabang_id'=>$request->cabangs[$i], 'jumlah'=>$request->jumlah[$i])
            );

            DB::table('data_stoks')->insert(
                array('id' => Str::uuid(), 'user' => Auth::user()->id, 'kuantiti'=>$request->jumlah[$i], 'created_at'=>$this->now(), 'updated_at'=>$this->now(), 'produk'=>$produk_id, 'cabang_id'=>$request->cabangs[$i], 'type'=>'Update Stock')
            );
        }

        return redirect()->to(route('daftar-produk'))->with('success','Berhasil Menambahkan Data');
    }
    public function edit($id){
        $this->fill();
        $data = $this->getMenuAll();
        $produk= new \App\produk();
        $data['data_produk'] = $produk->where('id',$id)->get();

        $stok_cabang = DB::table('produk_cabangs')
                            ->select('produk_cabangs.*')
                            ->where('produk_id', $id)->get();

        $data['stok_cabang'] = $stok_cabang;

        $distributors = DB::table('distributors')
                            ->select('*')
                            ->get();
        $data['distributors'] = $distributors;

        $coverage_areas = DB::table('coverage_areas')
                            ->select('*')
                            ->get();
        $data['coverage_areas'] = $coverage_areas;

        $cabangs = DB::table('cabangs')
                            ->select('*')
                            ->get();
        $data['cabangs'] = $cabangs;

        return view('admin.manajemen_produk.daftar_produk_edit',$data);
    }
    public function update(Request $request){
        $request->validate([
            'nama'  => 'required',
            'kode'   => 'required',
            'harga' => 'required',
            'distributor' => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'coverage_areas.*' => 'required',
            'cabangs.*' => 'required',
            'jumlah.*' => 'required',
        ]);

        $harga = str_replace(".","",$request->harga);

        if ($request->has('gambar')) {
            if ($request->has('old_image')) {
                $old_image = public_path().'/images/produks/'.$request->old_image;
                if(file_exists($old_image)){
                    unlink($old_image);
                }
            }
        }

        if ($request->has('gambar')) {
            $image = $request->file('gambar');
            $fileName = date('YmdHmi').'-'.$image->getClientOriginalName();

            $image->move(public_path('images/produks'), $fileName);
        }

        $produk = \App\produk::find($request->id);
        $produk->nama = $request->nama;
        $produk->kode = $request->kode;
        $produk->golongan = $request->golongan;
        $produk->harga = $harga;
        $produk->distributor_id = $request->distributor;
        if ($request->has('gambar')) {
            $produk->gambar = $fileName;
        }
        $produk->save();

        for ($i=0; $i < count($request->coverage_areas); $i++) {
            $check_data = DB::table('produk_cabangs')->select('produk_cabangs.*')->where('produk_id', $request->id)->where('cabang_id', $request->cabangs[$i])->get();

            if ($check_data->isNotEmpty()) {
                DB::table('produk_cabangs')
                    ->where('produk_id', $request->id)
                    ->where('cabang_id', $request->cabangs[$i])
                    ->update(array('jumlah' => $request->jumlah[$i]));
            } else {
                DB::table('produk_cabangs')->insert(
                    array('produk_id' => $request->id, 'coverage_area_id'=>$request->coverage_areas[$i], 'cabang_id'=>$request->cabangs[$i], 'jumlah'=>$request->jumlah[$i])
                );
            }

            DB::table('data_stoks')->insert(
                array('user' => Auth::user()->id, 'kuantiti'=>$request->jumlah[$i], 'created_at'=>$this->now(), 'updated_at'=>$this->now(), 'produk'=>$request->id, 'cabang_id'=>$request->cabangs[$i], 'type'=>'Update Stock')
            );

        }

        return redirect()->to(route('daftar-produk'))->with('success','Berhasil Mengubah Data');
    }
    public function delete(Request $request){
        $produk = \App\produk::find($request->id);
        $produk->delete();
        return redirect()->to(route('daftar-produk'))->with('success','Berhasil Menghapus Data');
    }
    public function export()
    {
        return Excel::download(new ProdukExport, 'daftar-produk.xlsx');
    }

    public function getCabangByArea($id)
    {
        $data_cabangs = "";
        if ($id != '0') {
            $data_cabangs = DB::table('cabangs')
                            ->select('cabangs.*')
                            ->where('cabangs.coverage_area_id', $id)->get();
        }

        echo json_encode($data_cabangs);
    }

    public function getProdukDetail($produk_id)
    {
        $data_produk = DB::table('produks')
                        ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
                        ->select('produks.*', 'distributors.nama_distributor')
                        ->where('produks.id', $produk_id)
                        ->get();

        $details = DB::table('produk_cabangs')
                        ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'produk_cabangs.coverage_area_id')
                        ->leftJoin('cabangs', 'cabangs.id', '=', 'produk_cabangs.cabang_id')
                        ->select('coverage_areas.nama_coverage_area', 'cabangs.nama_cabang', 'produk_cabangs.jumlah')
                        ->where('produk_cabangs.produk_id', $produk_id)
                        ->get();

        $data_produk[0]->details_stok = $details;

        echo json_encode($data_produk);
    }

    public function riwayatStok()
    {
        $this->fill();
        $data = $this->getMenuAll();
        $riwayat_stoks = DB::table('data_stoks')
                            ->leftJoin('produks', 'produks.id', '=', 'data_stoks.produk')
                            ->leftJoin('cabangs', 'cabangs.id', '=', 'data_stoks.cabang_id')
                            ->leftJoin('daftar_users', 'daftar_users.id', '=', 'data_stoks.user')
                            ->select('data_stoks.*', 'produks.nama as nama_produk', 'cabangs.nama_cabang', 'daftar_users.nama_user')
                            ->orderBy('data_stoks.created_at','desc')
                            ->get();

        $data['riwayat_stoks'] = $riwayat_stoks;
        return view('admin.manajemen_produk.riwayat_stok',$data);
    }

    public function deadStock()
    {
        $this->fill();
        $data = $this->getMenuAll();

        // $dead_stock = DB::table('produk_cabangs')
        //                     ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
        //                     ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'produk_cabangs.coverage_area_id')
        //                     ->leftJoin('cabangs', 'cabangs.id', '=', 'produk_cabangs.cabang_id')
        //                     ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
        //                     ->select('produk_cabangs.*', 'produks.nama as nama_produk', 'cabangs.nama_cabang', 'coverage_areas.nama_coverage_area', 'distributors.nama_distributor')
        //                     ->where('produk_cabangs.jumlah', 0)
        //                     ->get();
        $dt = Carbon::now();
        $last = $dt->subMonths(3);
        $tes = DB::select('SELECT distinct produk_cabangs.id, produk, real_sales.cabang_id FROM public.real_sales left join public.produk_cabangs on produk_cabangs.produk_id = real_sales.produk and produk_cabangs.cabang_id = real_sales.cabang_id where real_sales.bulan > \''.$last.'\' and status = \'Diterima\'');
        $produks_cabang = array();
        foreach ($tes as $key => $value) {
            array_push($produks_cabang, $value->id);
        }
        $dead_stock = DB::table('produk_cabangs')
                                ->leftJoin('produks', 'produks.id', '=', 'produk_cabangs.produk_id')
                                ->leftJoin('coverage_areas', 'coverage_areas.id', '=', 'produk_cabangs.coverage_area_id')
                                ->leftJoin('cabangs', 'cabangs.id', '=', 'produk_cabangs.cabang_id')
                                ->leftJoin('distributors', 'distributors.id', '=', 'produks.distributor_id')
                                ->select('produk_cabangs.*', 'produks.nama as nama_produk', 'cabangs.nama_cabang', 'coverage_areas.nama_coverage_area', 'distributors.nama_distributor')
                                ->whereNotIn('produk_cabangs.id',$produks_cabang)
                                ->get();
        $data['dead_stock'] = $dead_stock;
        return view('admin.manajemen_produk.dead_stok',$data);

    }
}
