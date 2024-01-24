<?php

use App\Models\Karyawan;
use App\Models\Transaksi;
use App\Models\Detailtransaksi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\JsexcelController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\BarangmasukController;
use App\Http\Controllers\PenyimpananController;
use App\Http\Controllers\BarangkeluarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DetailtransaksiController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\LaporanController;
use App\Models\Inventaris;

/*
|--------------------------------------------------------------------------
| Web Routes
|-------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->middleware('guest');


// routing login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/store', [LoginController::class, 'store']);
Route::get('/logout', [LoginController::class,'destroy'])->name("logout");

// routing auth akses
Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/api/masuk-autocomplete', [BarangmasukController::class, 'autocomplete']);
    Route::get('/api/keluar-autocomplete', [BarangkeluarController::class, 'autocomplete']);
    Route::get('/api/karyawan-autocomplete', [BarangkeluarController::class, 'autocomplete2']);
    Route::get('/api/karyawanedit-autocomplete', [BarangkeluarController::class, 'autocomplete3']);
    // Route::get('/api/barang-autocomplete2', [BarangmasukController::class, 'autocomplete2']);
    Route::get('/autocomplete/divisi', [BarangkeluarController::class, 'autocomplete4']);

    //Penyimpanan
    Route::get('/api/penyimpananin/{id}', [BarangmasukController::class, 'getPenyimpananByGudang']);
    Route::get('/api/penyimpananout/{id}', [BarangkeluarController::class, 'getPenyimpananByGudang2']);

    // test jexcel 1
    Route::get('/excel', [JsexcelController::class, 'index'])->name("excel");
    Route::get('/dataKaryawan', [JsexcelController::class, 'TabelKaryawanFix']);
    Route::put('/dataKaryawanup', [JsexcelController::class, 'karyawanUpdate']);
    Route::delete('/dataKaryawandel', [JsexcelController::class, 'karyawanDelete']);
    Route::get('/detailTransaksi', [DetailtransaksiController::class, 'TabelDetailFix']);
    //test jexcel 2
    Route::get('/excel2', [JsexcelController::class, 'index'])->name("excel2");


    // get datatables
    Route::get('/configuration/users/json', [UserController::class, 'getTable']);
    // Route::get('/configuration/karyawan/json', [KaryawanController::class, 'getTable']);
    Route::get('/configuration/roles/json', [RoleController::class, 'getTable']);
    Route::get('/configuration/permissions/json', [PermissionController::class, 'getTable']);

    // get data tables inventori transaksi barang
    Route::get('/transaksibarang/barang/json', [BarangController::class, 'getTable']);
    Route::get('/transaksibarang/kategori/json', [KategoriController::class, 'getTable']);
    Route::get('/transaksibarang/gudang/json', [GudangController::class, 'getTable']);
    Route::get('/transaksibarang/penyimpanan/json', [PenyimpananController::class, 'getTable']);
    Route::get('/transaksibarang/supplier/json', [SupplierController::class, 'getTable']);
    // Route::get('/transaksibarang/karyawan/json', [CustomerController::class, 'getTable']);
    Route::get('/transaksibarang/karyawan/json', [KaryawanController::class, 'getTable']);
    Route::get('/transaksibarang/transaksi/json', [TransaksiController::class, 'getTable']);
    Route::get('/transaksibarang/masuk/json', [BarangmasukController::class, 'getTable']);
    Route::get('/transaksibarang/keluar/json', [BarangkeluarController::class, 'getTable']);
    Route::get('/transaksibarang/divisi/json', [DivisiController::class, 'getTable']);
    Route::get('/transaksibarang/laporan/json', [LaporanController::class, 'getTable']);
    Route::get('/transaksibarang/inventaris/json', [InventarisController::class, 'getTable']);
    // cancel transaksi
    Route::put('/transaksibarang/masuk/{id}/cancel', [BarangmasukController::class, 'cancel'])->name('transaksibarang.masuk.cancel');
    Route::put('/transaksibarang/keluar/{id}/cancel', [BarangkeluarController::class, 'cancel2'])->name('transaksibarang.keluar.cancel');

    // reset password
    Route::get('/configuration/users/{user}/reset', [UserController::class, 'resetPass']);
    Route::post('/configuration/users/updatePass/{user}', [UserController::class, 'updatePass']);

    // resource
    Route::resource('/configuration/users', UserController::class);
    Route::resource('/configuration/roles', RoleController::class);
    Route::resource('/configuration/permissions', PermissionController::class);
    // Route::resource('/karyawan', KaryawanController::class);
    Route::resource('/databarang/barang', BarangController::class);
    Route::resource('/databarang/kategori', KategoriController::class);
    Route::resource('/lokasiinventori/gudang', GudangController::class);
    Route::resource('/lokasiinventori/penyimpanan', PenyimpananController::class);
    Route::resource('/supplier', SupplierController::class);
    Route::resource('/karyawan', KaryawanController::class);
    Route::resource('/transaksi', TransaksiController::class);
    Route::resource('/transaksibarang/masuk', BarangmasukController::class);
    Route::resource('/transaksibarang/keluar', BarangkeluarController::class);
    Route::resource('/divisi', DivisiController::class);
    // Route::resource('/report', ReportController::class);

    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/inventaris', [InventarisController::class, 'index']);

    //delete item in table
    // Route::delete('/delete-item-masuk', [BarangmasukController::class, 'deleteItem']);
    // Route::delete('/delete-item-keluar', [BarangmasukController::class, 'deleteItem']);

});




// testing spatie route
Route::put('post/publish', [DashboardController::class, 'publish'])->name('post.publish');
Route::put('post/unpublish', [DashboardController::class, 'unpublish'])->name('post.unpublish');

//Middleware all menu by route





