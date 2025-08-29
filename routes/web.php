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
use App\Http\Controllers\ParameterKasController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\CariPelangganController;
use App\Http\Controllers\BarangStokController;
use App\Http\Controllers\BarangTerjualController;
use App\Http\Controllers\BarangTerhapusController;
use App\Http\Controllers\TerimaBarangController;
use App\Http\Controllers\TransaksiBeliController;
use App\Http\Controllers\BatalBeliController;
use App\Http\Controllers\BatalJualController;
use App\Http\Controllers\RiwayatBatalJualController;
use App\Http\Controllers\TransaksiJualController;
use App\Http\Controllers\SelisihJualController;
use App\Http\Controllers\RiwayatBeliController;
use App\Http\Controllers\RiwayatBatalBeliController;
use App\Http\Controllers\DaftarPesananController;
use App\Http\Controllers\FormPenyimpananController;
use App\Http\Controllers\RiwayatCuciController;
use App\Http\Controllers\RiwayatPenyimpananController;
use App\Http\Controllers\FormRongsokController;
use App\Http\Controllers\RiwayatRongsokController;
use App\Http\Controllers\FormKetidaksesuaianController;
use App\Http\Controllers\RiwayatKetidaksesuaianController;
use App\Http\Controllers\SelisihBeliController;
use App\Http\Controllers\StokJualController;
// use App\Http\Controllers\;
// use App\Http\Controllers\;
// use App\Http\Controllers\;




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

//kondisi-datamaster
Route::get('/kondisi-utama', [KondisiBarangController::class, 'index'])->name('kondisibarang');
Route::post('/kondisi-store', [KondisiBarangController::class, 'store'])->name('kondisi.store');
Route::put('/kondisi-update/{id}', [KondisiBarangController::class, 'update'])->name('kondisi.update');
Route::delete('/kondisi-destroy/{id}', [KondisiBarangController::class, 'destroy'])->name('kondisi.destroy');

//Ongkos-datamaster
Route::get('/ongkos-utama', [OngkosController::class, 'index'])->name('ongkos');
Route::post('/ongkos-store', [OngkosController::class, 'store'])->name('ongkos.store');
Route::put('/ongkos-update/{id}', [OngkosController::class, 'update'])->name('ongkos.update');
Route::delete('/ongkos-destroy/{id}', [OngkosController::class, 'destroy'])->name('ongkos.destroy');

//Potongan-datamaster
Route::get('/potongan-utama', [PotonganController::class, 'index'])->name('potongan');
Route::post('/potongan', [PotonganController::class, 'store'])->name('potongan.store');
Route::put('/potongan/{id}', [PotonganController::class, 'update'])->name('potongan.update');
Route::delete('/potongan/{id}', [PotonganController::class, 'destroy'])->name('potongan.destroy');


//parameterkas-datamaster
Route::get('/parameter-kas', [ParameterKasController::class, 'index'])->name('parameterkas');
Route::post('/parameter-kas', [ParameterKasController::class, 'store'])->name('parameterkas.store');
Route::put('/parameter-kas/{id}', [ParameterKasController::class, 'update'])->name('parameterkas.update');
Route::delete('/parameter-kas/{id}', [ParameterKasController::class, 'destroy'])->name('parameterkas.destroy');

//staff-datamaster
Route::get('/staff', [StaffController::class, 'index'])->name('staff');
Route::post('/staff-store', [StaffController::class, 'store'])->name('staff.store');
Route::put('/staff-update/{kdstaff}', [StaffController::class, 'update'])->name('staff.update');
Route::delete('/staff-destroy/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

//pelanggan-datamaster
Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan');
Route::post('/pelanggan-store', [PelangganController::class, 'store'])->name('pelanggan.store');
Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
Route::put('/membership/update', [PelangganController::class, 'updateMembership'])->name('membership.update');
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

Route::get('/caripelanggan', [CariPelangganController::class, 'index'])->name('caripelanggan');
Route::get('/caripelanggan/search', [CariPelangganController::class, 'search'])->name('caripelanggan.search');


//dataBarang--Barang
Route::get('/StokBarang', [BarangStokController::class, 'index'])->name('barangStok');
Route::post('/StokBarang', [BarangStokController::class, 'store'])->name('barang.store');
Route::put('/StokBarang/{id}', [BarangStokController::class, 'update'])->name('barang.update');
Route::delete('/StokBarang/{id}', [BarangStokController::class, 'destroy'])->name('barang.destroy');


//BarangTerjual-barang
Route::get('/BarangTerjual', [BarangTerjualController::class, 'index'])->name('BarangTerjual');


//BarangTerhapus-Barang
Route::get('/BarangTerhapus', [BarangTerhapusController::class, 'index'])->name('barangterhapus');


//BarangTerima-Barang
Route::get('/terimabarang', [TerimaBarangController::class, 'index'])->name('terimabarang');


//BarangCuciSepuh-FormPenyimpanan
Route::get('/FormPenyimpanan', [FormPenyimpananController::class, 'index'])->name('formpenyimpanan');

//BarangCuciSepuh-RiwayatCuci
Route::get('/RiwayatCuci', [RiwayatCuciController::class, 'index'])->name('riwayatcuci');

//BarangCuciSepuh-RiwayatPenyimpanan
Route::get('/RiwayatPenyimpanan', [RiwayatPenyimpananController::class, 'index'])->name('riwayatpenyimpanan');

//BarangRongsok-FormRongsok
Route::get('/FormRongsok', [FormRongsokController::class, 'index'])->name('formrongsok');

//BarangRongsok-RiwayatRongsok
Route::get('/RiwayatRongsok', [RiwayatRongsokController::class, 'index'])->name('riwayatrongsok');

//BarangRongsok-FormKetidaksesuaian
Route::get('/FormKetidaksesuaian', [FormKetidaksesuaianController::class, 'index'])->name('formketidaksesuaian');

//BarangRongsok-RiwayatKetidaksesuaian
Route::get('/RiwayatKetidaksesuaian', [RiwayatKetidaksesuaianController::class, 'index'])->name('riwayatketidaksesuaian');

//BeliTransasksi-Beli
Route::get('/TransaksiBeli', [TransaksiBeliController::class, 'index'])->name('transaksibeli');

//BeliBatal-Beli
Route::get('/BatalBeli', [BatalBeliController::class, 'index'])->name('batalbeli');

//BeliRiwayat-Beli
Route::get('/RiwayatBeli', [RiwayatBeliController::class, 'index'])->name('riwayatbeli');

//BeliRiwayat-Batal-Beli
Route::get('/RiwayatBatalBeli', [RiwayatBatalBeliController::class, 'index'])->name('riwayatbatalbeli');

//BeliSelisih-Batal-Beli
Route::get('/SelisihBeli', [SelisihBeliController::class, 'index'])->name('selisihbelibatal');

//JualBatal-jual
Route::get('/BatalJual', [BatalJualController::class, 'index'])->name('bataljual');

//JualRiwayat-Batal-jual
Route::get('/RiweayatBatalJual', [RiwayatBatalJualController::class, 'index'])->name('riwayatbataljual');

//JualTransaksi-jual
Route::get('/TransaksiJual', [TransaksiJualController::class, 'index'])->name('transpenjualan');

//JualSelisih-jual
Route::get('/SelisihJual', [SelisihJualController::class, 'index'])->name('selisihjual');

//stokjual-jual
Route::get('/stokjual', [StokJualController::class, 'index'])->name('stokjual');
Route::post('/stokjual', [StokJualController::class, 'store'])->name('stokjual.store');
Route::put('/stokjual/{nofaktur}', [StokJualController::class, 'update'])->name('stokjual.update');
Route::delete('/stokjual/{nofaktur}', [StokJualController::class, 'destroy'])->name('stokjual.destroy');


//daftarPesanan- pesan
Route::get('/daftarPesanan', [DaftarPesananController::class, 'index'])->name('daftarpesanan');



//backup_data-utility
Route::get('/backup', [BackupController::class, 'index'])->name('backup');
// Route::post('/backup', [BackupController::class, 'backup'])->name('backup.run');
// Route::post('/restore', [BackupController::class, 'restore'])->name('restore.run');


Route::get('/', function () {
    return view('login');
});
