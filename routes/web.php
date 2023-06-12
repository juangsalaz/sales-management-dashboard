<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LoginController@index')->middleware('guest')->name('login');

Route::get('/login', 'LoginController@index')->middleware('guest')->name('login');
Route::post('/login', 'LoginController@autentikasi');
Route::get('/logout', 'LoginController@logout')->name('logout');

Route::group(['prefix'=>'/admin','middleware' => 'auth:pengguna'],function (){
    //----- Dashboard -----//
    Route::get('/backup', 'DashboardController@backup')->name('dashboard-backup');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('/dashboard/real-sales-data', 'DashboardController@realSalesData')->name('dashboard-real-sales-data');
    Route::post('/dashboard/stok-produk', 'DashboardController@stokProdukData')->name('dashboard-stok-produk-data');
    Route::post('/dashboard/real_sales_by_estimsi', 'DashboardController@realSalesByEstimasi')->name('dashboard-real-sales-by-estimasi-data');
    Route::post('/dashboard/stok-produk-dist/{id}', 'DashboardController@stokProdukDataByDistributor')->name('dashboard-stok-produk-distributor-data');

    //----- Manajemen User -----//
    //----- Daftar Bagian -----//
    Route::get('/manajemen-user/daftar-bagian', 'daftarBagianController@index')->name('daftar-bagian');
    Route::get('/manajemen-user/daftar-bagian/add', 'daftarBagianController@tambah')->name('daftar-bagian-add');
    Route::post('/manajemen-user/daftar-bagian/store', 'daftarBagianController@store')->name('daftar-bagian-store');
    Route::get('/manajemen-user/daftar-bagian/edit/{id}', 'daftarBagianController@edit')->name('daftar-bagian-edit');
    Route::post('/manajemen-user/daftar-bagian/update/{id}', 'daftarBagianController@update')->name('daftar-bagian-update');
    Route::post('/manajemen-user/daftar-bagian/delete/{id}', 'daftarBagianController@delete')->name('daftar-bagian-delete');
    Route::get('/manajemen-user/daftar-bagian/export', 'daftarBagianController@export')->name('daftar-bagian-export');

    //----- Daftar Jabatan -----//
    Route::get('/manajemen-user/daftar-jabatan', 'DaftarJabatanController@index')->name('daftar-jabatan');
    Route::get('/manajemen-user/daftar-jabatan/add', 'DaftarJabatanController@tambah')->name('daftar-jabatan-add');
    Route::post('/manajemen-user/daftar-jabatan/store', 'DaftarJabatanController@store')->name('daftar-jabatan-store');
    Route::get('/manajemen-user/daftar-jabatan/edit/{id}', 'DaftarJabatanController@edit')->name('daftar-jabatan-edit');
    Route::post('/manajemen-user/daftar-jabatan/update/{id}', 'DaftarJabatanController@update')->name('daftar-jabatan-update');
    Route::post('/manajemen-user/daftar-jabatan/delete/{id}', 'DaftarJabatanController@delete')->name('daftar-jabatan-delete');
    Route::get('/manajemen-user/daftar-jabatan/export', 'DaftarJabatanController@export')->name('daftar-jabatan-export');

    //----- Daftar User -----//
    Route::get('/manajemen-user/daftar-user', 'DaftarUserController@index')->name('daftar-user');
    Route::get('/manajemen-user/daftar-user/add', 'DaftarUserController@tambah')->name('daftar-user-add');
    Route::post('/manajemen-user/daftar-user/store', 'DaftarUserController@store')->name('daftar-user-store');
    Route::get('/manajemen-user/daftar-user/edit/{id}', 'DaftarUserController@edit')->name('daftar-user-edit');
    Route::post('/manajemen-user/daftar-user/update/{id}', 'DaftarUserController@update')->name('daftar-user-update');
    Route::post('/manajemen-user/daftar-user/delete/{id}', 'DaftarUserController@delete')->name('daftar-user-delete');
    Route::get('/manajemen-user/daftar-user/export', 'DaftarUserController@export')->name('daftar-user-export');

    //----- Otoritas Menu -----//
    Route::get('/manajemen-user/otoritas-menu', 'OtoritasMenuController@index')->name('otoritas-menu');
    Route::get('/manajemen-user/otoritas-menu/add', 'OtoritasMenuController@tambah')->name('otoritas-menu-add');
    Route::post('/manajemen-user/otoritas-menu/store', 'OtoritasMenuController@store')->name('otoritas-menu-store');
    Route::get('/manajemen-user/otoritas-menu/edit/{id}', 'OtoritasMenuController@edit')->name('otoritas-menu-edit');
    Route::post('/manajemen-user/otoritas-menu/update/{id}', 'OtoritasMenuController@update')->name('otoritas-menu-update');
    Route::post('/manajemen-user/otoritas-menu/delete/{id}', 'OtoritasMenuController@delete')->name('otoritas-menu-delete');
    Route::get('/manajemen-user/otoritas-menu/export', 'OtoritasMenuController@export')->name('otoritas-menu-export');

    //----- Daftar Produk -----//
    Route::get('/manajemen-produk/daftar-produk', 'DaftarProdukController@index')->name('daftar-produk');
    Route::get('/manajemen-produk/daftar-produk/add', 'DaftarProdukController@tambah')->name('daftar-produk-add');
    Route::post('/manajemen-produk/daftar-produk/store', 'DaftarProdukController@store')->name('daftar-produk-store');
    Route::get('/manajemen-produk/daftar-produk/edit/{id}', 'DaftarProdukController@edit')->name('daftar-produk-edit');
    Route::post('/manajemen-produk/daftar-produk/update/{id}', 'DaftarProdukController@update')->name('daftar-produk-update');
    Route::post('/manajemen-produk/daftar-produk/delete/{id}', 'DaftarProdukController@delete')->name('daftar-produk-delete');
    Route::get('/manajemen-produk/daftar-produk/export', 'DaftarProdukController@export')->name('daftar-produk-export');
    Route::post('/manajemen-produk/get-cabang-by-area/{id}', 'DaftarProdukController@getCabangByArea')->name('cabang-by-area');
    Route::post('/manajemen-produk/produk-details/{id}', 'DaftarProdukController@getProdukDetail')->name('produk-detail');

    Route::get('/manajemen-produk/riwayat-stok', 'DaftarProdukController@riwayatStok')->name('produk-riwayat');
    Route::get('/manajemen-produk/death-stok', 'DaftarProdukController@deadStock')->name('produk-dead-stok');

    //cabang
    Route::get('/manajemen-area/daftar-cabang', 'daftarCabangController@index')->name('daftar-cabang');
    Route::get('/manajemen-area/daftar-cabang/add', 'daftarCabangController@add')->name('daftar-cabang-add');
    Route::post('/manajemen-area/daftar-cabang/store', 'daftarCabangController@store')->name('daftar-cabang-store');
    Route::get('/manajemen-area/daftar-cabang/edit/{id}', 'daftarCabangController@edit')->name('daftar-cabang-edit');
    Route::post('/manajemen-area/daftar-cabang/update/{id}', 'daftarCabangController@update')->name('daftar-cabang-update');
    Route::post('/manajemen-area/daftar-cabang/delete/{id}', 'daftarCabangController@delete')->name('daftar-cabang-delete');
    Route::get('/manajemen-area/daftar-cabang/export', 'daftarCabangController@export')->name('daftar-cabang-export');

    //coverage area
    Route::get('/manajemen-area/daftar-coverage-area', 'daftarCoverageAreaController@index')->name('daftar-coverage-area');
    Route::get('/manajemen-area/daftar-coverage-area/add', 'daftarCoverageAreaController@add')->name('daftar-coverage-area-add');
    Route::post('/manajemen-area/daftar-coverage-area/store', 'daftarCoverageAreaController@store')->name('daftar-coverage-area-store');
    Route::get('/manajemen-area/daftar-coverage-area/edit/{id}', 'daftarCoverageAreaController@edit')->name('daftar-coverage-area-edit');
    Route::post('/manajemen-area/daftar-coverage-area/update/{id}', 'daftarCoverageAreaController@update')->name('daftar-coverage-area-update');
    Route::post('/manajemen-area/daftar-coverage-area/delete/{id}', 'daftarCoverageAreaController@delete')->name('daftar-coverage-area-delete');
    Route::get('/manajemen-area/daftar-coverage-area/export', 'daftarCoverageAreaController@export')->name('daftar-coverage-area-export');

    //daftar distributor
    Route::get('/manajemen-distributor/daftar-distributor', 'daftarDistributorController@index')->name('daftar-distributor');

    //daftar outlet transaksi
    Route::get('/manajemen-transaksi/manajemen-outlet', 'daftarOutletTransaksiController@index')->name('daftar-outlet');
    Route::get('/manajemen-transaksi/manajemen-outlet/add', 'daftarOutletTransaksiController@add')->name('daftar-outlet-add');
    Route::post('/manajemen-transaksi/manajemen-outlet/store', 'daftarOutletTransaksiController@store')->name('daftar-outlet-store');
    Route::get('/manajemen-transaksi/manajemen-outlet/edit/{id}', 'daftarOutletTransaksiController@edit')->name('daftar-outlet-edit');
    Route::post('/manajemen-transaksi/manajemen-outlet/update/{id}', 'daftarOutletTransaksiController@update')->name('daftar-outlet-update');
    Route::post('/manajemen-transaksi/manajemen-outlet/delete/{id}', 'daftarOutletTransaksiController@delete')->name('daftar-outlet-delete');
    Route::get('/manajemen-transaksi/manajemen-outlet/export', 'daftarOutletTransaksiController@export')->name('daftar-outlet-export');

    //data distributor
    Route::get('/manajemen-distributor/data-distributor', 'dataDistributorController@index')->name('data-distributor');
    Route::get('/manajemen-distributor/data-distributor/add', 'dataDistributorController@add')->name('data-distributor-add');
    Route::post('/manajemen-distributor/data-distributor/store', 'dataDistributorController@store')->name('data-distributor-store');
    Route::get('/manajemen-distributor/data-distributor/edit/{id}', 'dataDistributorController@edit')->name('data-distributor-edit');
    Route::post('/manajemen-distributor/data-distributor/update/{id}', 'dataDistributorController@update')->name('data-distributor-update');
    Route::post('/manajemen-distributor/data-distributor/delete/{id}', 'dataDistributorController@delete')->name('data-distributor-delete');
    Route::get('/manajemen-distributor/data-distributor/export', 'dataDistributorController@export')->name('data-distributor-export');

    //estimasi
    Route::get('/manajemen-marketing/estimasi', 'estimasiController@index')->name('estimasi');
    Route::post('/manajemen-marketing/estimasi', 'estimasiController@index')->name('estimasi');
    Route::get('/manajemen-marketing/estimasi/add', 'estimasiController@add')->name('estimasi-add');
    Route::post('/manajemen-marketing/estimasi/store', 'estimasiController@store')->name('estimasi-store');
    Route::get('/manajemen-marketing/estimasi/edit/{id}', 'estimasiController@edit')->name('estimasi-edit');
    Route::get('/manajemen-marketing/estimasi/status/{id}', 'estimasiController@status')->name('estimasi-status');
    Route::post('/manajemen-marketing/estimasi/status-update/{id}', 'estimasiController@statusUpdates')->name('estimasi-status-update');
    Route::post('/manajemen-marketing/estimasi/update/{id}', 'estimasiController@update')->name('estimasi-update');
    Route::post('/manajemen-marketing/estimasi/delete/{id}', 'estimasiController@delete')->name('estimasi-delete');
    Route::post('/manajemen-marketing/estimasi/estimasi-details/{id}', 'estimasiController@getEstimasiDetails')->name('estimasi-status-details');
    Route::get('/manajemen-marketing/estimasi/export', 'estimasiController@export')->name('estimasi-export');
    Route::post('/manajemen-marketing/estimasi/get-coverage-area/{id}', 'estimasiController@getCoverageAreaByUser')->name('estimasi-coverage-area');
    Route::post('/manajemen-marketing/estimasi/get-cabang-area/{id}', 'estimasiController@getCabangByArea')->name('estimasi-cabang');
    Route::post('/manajemen-marketing/estimasi/get-distributor/{id}', 'estimasiController@getDistributorByCabang')->name('estimasi-dist');
    Route::post('/manajemen-marketing/estimasi/get-produks/{id}/{cabang_id}', 'estimasiController@getProdukByDist')->name('estimasi-produk');
    Route::post('/manajemen-marketing/estimasi/update-status', 'estimasiController@updateStatus')->name('estimasi-update-status');

    //real sales
    Route::get('/manajemen-marketing/real-sales', 'realsalesController@index')->name('realsales');
    Route::post('/manajemen-marketing/real-sales', 'realsalesController@index')->name('realsales');
    Route::get('/manajemen-marketing/real-sales/add', 'realsalesController@add')->name('realsales-add');
    Route::post('/manajemen-marketing/real-sales/store', 'realsalesController@store')->name('realsales-store');
    Route::get('/manajemen-marketing/real-sales/edit/{id}', 'realsalesController@edit')->name('realsales-edit');
    Route::post('/manajemen-marketing/real-sales/update/{id}', 'realsalesController@update')->name('realsales-update');
    Route::post('/manajemen-marketing/real-sales/delete/{id}', 'realsalesController@delete')->name('realsales-delete');
    Route::get('/manajemen-marketing/real-sales/status/{id}', 'realsalesController@status')->name('realsales-status');
    Route::post('/manajemen-marketing/real-sales/status-update/{id}', 'realsalesController@statusUpdate')->name('realsales-status-update');
    Route::post('/manajemen-marketing/real-sales/real-sales-details/{id}', 'realsalesController@getRealSalesDetails')->name('realsales-status-details');
    Route::get('/manajemen-marketing/real-sales/export', 'realsalesController@export')->name('realsales-export');
    Route::post('/manajemen-marketing/real-sales/update-status', 'realsalesController@updateStatus')->name('realsales-update-status');

    Route::post('/manajemen-marketing/real-sales/get-coverage-area/{id}', 'realsalesController@getCoverageAreaByUser')->name('realsales-coverage-area');
    Route::post('/manajemen-marketing/real-sales/get-cabang-area/{id}', 'realsalesController@getCabangByArea')->name('realsales-cabang');
    Route::post('/manajemen-marketing/real-sales/get-distributor/{id}', 'realsalesController@getDistributorByCabang')->name('realsales-dist');
    Route::post('/manajemen-marketing/real-sales/get-produks/{id}', 'realsalesController@getProdukByDist')->name('realsales-produk');

    //target distributor
    Route::get('/manajemen-marketing/target-distributor', 'targetDistributorController@index')->name('target-distributor');
    Route::get('/manajemen-marketing/target-distributor/add', 'targetDistributorController@add')->name('target-distributor-add');
    Route::post('/manajemen-marketing/target-distributor/store', 'targetDistributorController@store')->name('target-distributor-store');
    Route::get('/manajemen-marketing/target-distributor/edit/{id}', 'targetDistributorController@edit')->name('target-distributor-edit');
    Route::post('/manajemen-marketing/target-distributor/update/{id}', 'targetDistributorController@update')->name('target-distributor-update');
    Route::post('/manajemen-marketing/target-distributor/delete/{id}', 'targetDistributorController@delete')->name('target-distributor-delete');
    Route::post('/manajemen-marketing/target-distributor/details/{id}', 'targetDistributorController@details')->name('target-distributor-details');
    Route::get('/manajemen-marketing/target-distributor/export', 'targetDistributorController@export')->name('target-distributor-export');

    //data stok
    Route::get('/manajemen-stok/data-stok', 'dataStokController@index')->name('data-stok');
    Route::get('/manajemen-stok/data-stok/add', 'dataStokController@add')->name('data-stok-add');
    Route::post('/manajemen-stok/data-stok/store', 'dataStokController@store')->name('data-stok-store');
    Route::get('/manajemen-stok/data-stok/edit/{id}', 'dataStokController@edit')->name('data-stok-edit');
    Route::post('/manajemen-stok/data-stok/update/{id}', 'dataStokController@update')->name('data-stok-update');
    Route::post('/manajemen-stok/data-stok/delete/{id}', 'dataStokController@delete')->name('data-stok-delete');
    Route::get('/manajemen-stok/data-stok/export', 'dataStokController@export')->name('data-stok-export');

    //edit password
    Route::get('/settings/edit-password', 'settingsController@editPassword')->name('setting-edit-password');
    Route::post('/settings/edit-password/update', 'settingsController@editPasswordUpdate')->name('setting-edit-password-update');

    //running text
    Route::get('/settings/running-text', 'settingsController@runningText')->name('setting-running-text');
    Route::post('/settings/running-text/update', 'settingsController@runningTextUpdate')->name('setting-running-text-update');

    //pengingat
    Route::get('/pengingat', 'pengingatController@index')->name('pengingat');
    Route::get('/pengingat/add', 'pengingatController@add')->name('pengingat-add');
    Route::post('/pengingat/store', 'pengingatController@store')->name('pengingat-store');
    Route::get('/pengingat/edit/{id}', 'pengingatController@edit')->name('pengingat-edit');
    Route::post('/pengingat/update/{id}', 'pengingatController@update')->name('pengingat-update');
    Route::post('/pengingat/delete/{id}', 'pengingatController@delete')->name('pengingat-delete');

    //trash
    Route::get('/trash', 'trashController@index')->name('trash');
    Route::post('/trash/restore', 'trashController@restore')->name('trash-restore');
    Route::post('/trash/restore-real-sales', 'trashController@restoreRealSales')->name('trash-restore-real-sales');
    Route::post('/trash/restore-produks', 'trashController@restoreProduks')->name('trash-restore-produks');

    //notifikasi
    Route::get('/notifikasi', 'NotifikasiController@index')->name('notifikasi');
    Route::post('/notifikasi/update-status', 'NotifikasiController@updateStatus')->name('notif-update-status');
    Route::post('/notifikasi/filter', 'NotifikasiController@filter')->name('notif-filter');

    //laporan
    Route::get('/laporan', 'laporanController@index')->name('laporan');
    Route::post('/laporan/datas', 'laporanController@grafikData')->name('laporan-data');
});
