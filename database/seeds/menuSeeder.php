<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class menuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // ----- Dashboard ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Dashboard';
        $model->status = 1;
        $model->slug = '/admin/dashboard';
        $model->slug_icon = 'images/icon/dashboard.png';
        $model->urutan = 1;
        $model->save();

        // ----- Manajemen User ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen User';
        $model->status = 1;
        $model->slug_icon = 'images/icon/user.png';
        $model->urutan = 2;
        $model->save();
        $id_menu = $model->select('id')->where('nama','Manajemen User')->get();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Bagian';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-bagian';
        $model->urutan = 1;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar Bagian')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-bagian/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-bagian/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-bagian/delete';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Jabatan';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-jabatan';
        $model->urutan = 2;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar Jabatan')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-jabatan/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-jabatan/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-jabatan/delete';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar User';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-user';
        $model->urutan = 3;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar User')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-user/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-user/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/daftar-user/delete';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Otoritas Menu';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-user/otoritas-menu';
        $model->urutan = 4;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Otoritas Menu')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/otoritas-menu/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/otoritas-menu/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-user/otoritas-menu/delete';
        $model->save();

        // ----- Manajemen Produk ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Produk';
        $model->status = 1;
        $model->slug_icon = 'images/icon/produk.png';
        $model->urutan = 3;
        $model->save();
        $id_menu = $model->select('id')->where('nama','Manajemen Produk')->get();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Produk';
        $model->nama = 'Daftar Produk dan Harga';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-produk/daftar-produk';
        $model->urutan = 1;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar Produk dan Harga')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'View';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-produk/daftar-produk/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-produk/daftar-produk/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-produk/daftar-produk/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-produk/daftar-produk/delete';
        $model->save();

        // ----- Manajemen Transaksi ----- //
        $model = new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Transaksi';
        $model->status = 1;
        $model->slug_icon = 'images/icon/transaksi.png';
        $model = new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Riwayat Stok';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-produk/riwayat-stok';
        $model->urutan = 2;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Riwayat Stok')->get();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Outlet Transaksi';
        $model->nama = 'Dead Stock';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-produk/death-stok';
        $model->urutan = 3;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Dead Stock')->get();

        // ----- Manajemen Area ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Area';
        $model->status = 1;
        $model->slug_icon = 'images/icon/stok.png';
        $model->urutan = 4;
        $model->save();
        $id_menu = $model->select('id')->where('nama','Manajemen Area')->get();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Coverage Area';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-coverage-area';
        $model->urutan = 1;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar Coverage Area')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-coverage-area/add';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-coverage-area/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-coverage-area/delete';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Cabang';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-cabang';
        $model->urutan = 2;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar Cabang')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-cabang/add';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-cabang/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-area/daftar-cabang/delete';
        $model->save();

        // ----- Manajemen Distributor ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Distributor';
        $model->status = 1;
        $model->slug_icon = 'images/icon/distributor.png';
        $model->urutan = 5;
        $model->save();
        $id_menu = $model->select('id')->where('nama','Manajemen Distributor')->get();

        $model = new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Coverage Area';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/daftar-coverage-area';
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar Coverage Area')->get();

        // $model =new \App\sub_menu2();
        // $model->id = Str::uuid();
        // $model->nama = 'Tambah';
        // $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        // $model->slug = '/admin/daftar-coverage-area/tambah';
        // $model->save();
        // $model =new \App\sub_menu2();
        // $model->id = Str::uuid();
        // $model->nama = 'Edit';
        // $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        // $model->slug = '/admin/daftar-coverage-area/edit';
        // $model->save();
        // $model =new \App\sub_menu2();
        // $model->id = Str::uuid();
        // $model->nama = 'Hapus';
        // $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        // $model->slug = '/admin/daftar-coverage-area/delete';
        // $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Distributor';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/daftar-distributor';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Daftar Cabang';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/daftar-cabang';
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Daftar Cabang')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/daftar-cabang/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/daftar-cabang/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/daftar-cabang/delete';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Data Cabang';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/data-cabang';
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Data Cabang')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/data-cabang/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/data-cabang/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/data-cabang/delete';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Data Distributor';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-distributor/data-distributor';
        $model->urutan = 2;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Data Distributor')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-distributor/data-distributor/add';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-distributor/data-distributor/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-distributor/data-distributor/delete';
        $model->save();
        
        // ----- Manajemen Transaksi ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Transaksi';
        $model->status = 1;
        $model->slug_icon = 'images/icon/transaksi.png';
        $model->urutan = 6;
        $model->save();
        $id_menu = $model->select('id')->where('nama','Manajemen Transaksi')->get();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Outlet';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-transaksi/manajemen-outlet';
        $model->urutan = 1;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Manajemen Outlet')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-transaksi/manajemen-outlet/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-transaksi/manajemen-outlet/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-transaksi/manajemen-outlet/delete';
        $model->save();

        // ----- Manajemen Marketing ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Marketing';
        $model->status = 1;
        $model->slug_icon = 'images/icon/marketing.png';
        $model->urutan = 7;
        $model->save();
        $id_menu = $model->select('id')->where('nama','Manajemen Marketing')->get();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Estimasi';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-marketing/estimasi';
        $model->urutan = 1;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Estimasi')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'View';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/estimasi/view';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/estimasi/add';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/estimasi/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/estimasi/delete';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Status';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/estimasi/status';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Real Sales';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-marketing/real-sales';
        $model->urutan = 2;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Real Sales')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'View';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/real-sales/view';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/real-sales/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/real-sales/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/real-sales/delete';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Status';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/real-sales/status';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Target Distributor';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/manajemen-marketing/target-distributor';
        $model->urutan = 3;
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Target Distributor')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'View';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/target-distributor/view';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/target-distributor/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/target-distributor/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/manajemen-marketing/target-distributor/delete';
        $model->save();

        // ----- Manajemen Stok ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manajemen Stok';
        $model->status = 1;
        $model->slug_icon = 'images/icon/stok.png';
        $model->save();
        $id_menu = $model->select('id')->where('nama','Manajemen Stok')->get();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Data Stok';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/data-stok';
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Data Stok')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/data-stok/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/data-stok/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/data-stok/delete';
        $model->save();

        $model =new \App\sub_menu1();
        $model->id = Str::uuid();
        $model->nama = 'Death Stok';
        $model->id_menu_utama = $id_menu[0]['id'];
        $model->slug = '/admin/death-stok';
        $model->save();
        $id_sub_menu1 = $model->select('id')->where('nama','Death Stok')->get();

        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Tambah';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/death-stok/tambah';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Edit';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/death-stok/edit';
        $model->save();
        $model =new \App\sub_menu2();
        $model->id = Str::uuid();
        $model->nama = 'Hapus';
        $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        $model->slug = '/admin/death-stok/delete';
        $model->save();
        // // ----- Manajemen Stok ----- //
        // $model =new \App\menu_utama();
        // $model->nama = 'Manajemen Stok';
        // $model->status = 1;
        // $model->slug_icon = 'images/icon/stok.png';
        // $model->urutan = 7;
        // $model->save();
        // $id_menu = $model->select('id')->where('nama','Manajemen Stok')->get();

        // $model =new \App\sub_menu1();
        // $model->nama = 'Data Stok';
        // $model->id_menu_utama = $id_menu[0]['id'];
        // $model->slug = '/admin/manajemen-stok/data-stok';
        // $model->urutan = 1;
        // $model->save();
        // $id_sub_menu1 = $model->select('id')->where('nama','Data Stok')->get();

        // $model =new \App\sub_menu2();
        // $model->nama = 'Tambah';
        // $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        // $model->slug = '/admin/manajemen-stok/data-stok/tambah';
        // $model->save();
        // $model =new \App\sub_menu2();
        // $model->nama = 'Edit';
        // $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        // $model->slug = '/admin/manajemen-stok/data-stok/edit';
        // $model->save();
        // $model =new \App\sub_menu2();
        // $model->nama = 'Hapus';
        // $model->id_sub_menu1 = $id_sub_menu1[0]['id'];
        // $model->slug = '/admin/manajemen-stok/data-stok/delete';
        // $model->save();

        // ----- Laporan ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Laporan';
        $model->status = 1;
        $model->slug = '/admin/laporan';
        $model->slug_icon = 'images/icon/laporan.png';
        $model->urutan = 8;
        $model->save();

        // ----- Pengingat ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Pengingat';
        $model->status = 1;
        $model->slug = '/admin/pengingat';
        $model->slug_icon = 'images/icon/pengingat.png';
        $model->urutan = 9;
        $model->save();

        // ----- Trash ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Trash';
        $model->status = 1;
        $model->slug = '/admin/trash';
        $model->slug_icon = 'images/icon/delete.png';
        $model->urutan = 10;
        $model->save();

        // ----- Running Text ----- //
        $model =new \App\menu_utama();
        $model->id = Str::uuid();
        $model->nama = 'Manage Running Text';
        $model->status = 1;
        $model->slug = '/admin/settings/running-text';
        $model->slug_icon = 'images/icon/delete.png';
        $model->urutan = 11;
        $model->save();
    }
}
