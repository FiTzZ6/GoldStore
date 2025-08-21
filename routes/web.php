<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\JualController;
use App\Http\Controllers\uang_kas\KasController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\BakiBarangController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\KondisiBarangController;
use App\Http\Controllers\OngkosController;
use App\Http\Controllers\PotonganController;
use App\Http\Controllers\BackupController;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'masuk'])->name('login.masuk');
Route::post('/login', [LoginController::class, 'masuk'])->name('login.proses');
Route::post('/register', [LoginController::class, 'daftarakun'])->name('register');
Route::get('/register', [LoginController::class, 'formregister'])->name('register');
Route::post('/register', [LoginController::class, 'daftarakun'])->name('register.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/profile', function () {
    return view('utility.akun.profile');
})->name('utility.akun.profile');

Route::get('/jual', [JualController::class, 'index'])->name('jual.index');

Route::get('/laporan/dashboard', [LaporanController::class, 'dashboard'])->name('laporan.dashboard');

Route::get('/utility/company-profile', [UtilityController::class, 'companyProfile'])
    ->name('utility.company.company_profile');
Route::post('/utility/company-profile', [UtilityController::class, 'setUpCompany'])
    ->name('utility.company.set_up_company');

// Formulir PP
Route::get('/formulir_pp', [UtilityController::class, 'index'])
    ->name('formulir_pp.index'); // halaman list atau index
Route::get('/formulir_pp/create', [UtilityController::class, 'create'])
    ->name('formulir_pp.create'); // halaman form input
Route::post('/utility/permintaan-pembelian/kirim', [UtilityController::class, 'kirimPP'])
    ->name('utility.kirim_pp');

// Akun Management
Route::get('/utility/akun', [LoginController::class, 'listAkun'])
    ->name('utility.akun.table_akun');
Route::get('/utility/akun/create', [LoginController::class, 'createAkun'])
    ->name('user.create');
Route::post('/utility/akun', [LoginController::class, 'storeAkun'])
    ->name('user.store');
Route::get('/utility/akun/{id}/edit', [LoginController::class, 'editAkun'])
    ->name('user.edit');
Route::put('/utility/akun/{id}', [LoginController::class, 'updateAkun'])
    ->name('user.update');
Route::delete('/utility/akun/{id}', [LoginController::class, 'destroyAkun'])
    ->name('user.destroy');

// Permintaan Pembelian
Route::get('/utility/permintaan-pembelian', [UtilityController::class, 'permintaanPembelian'])
    ->name('utility.permintaan_pembelian.index');


//SUPPLIER -datamaster
Route::get('/UTAMA', [SupplierController::class, 'index'])->name('supplier');
Route::post('/tambah-supplier', [SupplierController::class, 'store'])->name('supplier.store');
Route::put('/update-supplier/{kdsupplier}', [SupplierController::class, 'update'])->name('supplier.update');
Route::delete('/hapus-supplier/{kdsupplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

//area-datamaster
Route::get('/area-utama', [AreaController::class, 'index'])->name('area');
Route::post('/tambah-area', [AreaController::class, 'store'])->name('area.store');
Route::put('/update-area/{kdarea}', [AreaController::class, 'update'])->name('area.update');
Route::delete('/hapus-area/{kdarea}', [AreaController::class, 'destroy'])->name('area.destroy');

//cabang-datamaster
Route::get('/cabang-utama', [CabangController::class, 'index'])->name('cabang');
Route::post('/tambah-cabang', [CabangController::class, 'store'])->name('store');
Route::put('/update-cabang/{kdtoko}', [CabangController::class, 'update'])->name('update');
Route::delete('/hapus-cabang/{kdtoko}', [CabangController::class, 'destroy'])->name('destroy');

Route::prefix('uang-kas')->name('uang_kas.')->group(function () {
    Route::get('/', [KasController::class, 'index'])->name('index');
    Route::post('/', [KasController::class, 'store'])->name('store');
    Route::put('/{id}', [KasController::class, 'update'])->name('update');
    Route::delete('/{id}', [KasController::class, 'destroy'])->name('destroy');
});

//baki_barang-datamaster
Route::get('/bakibarang', [BakiBarangController::class, 'index'])->name('bakibarang.index');
Route::post('/bakibarang', [BakiBarangController::class, 'store'])->name('bakibarang.store');
Route::put('/bakibarang/{kdbaki}', [BakiBarangController::class, 'update'])->name('bakibarang.update');
Route::delete('/bakibarang/{id}', [BakiBarangController::class, 'destroy'])->name('bakibarang.destroy');

//jenis_barang-datamaster
Route::get('/jenis-barang', [JenisBarangController::class, 'index'])->name('jenisbarang');
Route::post('/tambah-jenis', [JenisBarangController::class, 'store'])->name('jenis.store');
Route::put('/update-jenis/{kdjenis}', [JenisBarangController::class, 'update'])->name('jenis.update');
Route::delete('/hapus-jenis/{kdjenis}', [JenisBarangController::class, 'destroy'])->name('jenis.destroy');

//kategori_barang-datamaster
Route::get('/kategori-utama', [KategoriBarangController::class, 'index'])->name('kategoribarang');
Route::post('/tambah-kategori', [KategoriBarangController::class, 'store'])->name('kategori.store');
Route::put('/update-kategori/{kdkategori}', [KategoriBarangController::class, 'update'])->name('kategori.update');
Route::delete('/hapus-kategori/{kdkategori}', [KategoriBarangController::class, 'destroy'])->name('kategori.destroy');


Route::get('/kondisi-utama', [KondisiBarangController::class, 'index'])->name('kondisibarang');

Route::get('/ongkos-utama', [OngkosController::class, 'index'])->name('ongkos');

Route::get('/potongan-utama', [PotonganController::class, 'index'])->name('potongan');


//backup_data-utility
Route::get('/backup', [BackupController::class, 'index'])->name('backup');
Route::post('/backup', [BackupController::class, 'backup'])->name('backup.run');
Route::post('/restore', [BackupController::class, 'restore'])->name('restore.run');


Route::get('/', function () {
    return view('login');
});
