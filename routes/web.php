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
use App\Http\Controllers\RiwayatPenjualanController;
use App\Http\Controllers\PindahBakiController;
use App\Http\Controllers\RiwayatPindahBarangController;
use App\Http\Controllers\TransaksiServiceController;
use App\Http\Controllers\DaftarServiceController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\QRDirekturController;
use App\Http\Controllers\BarangCepatLakuController;
use App\Http\Controllers\BarangLambatLakuController;
use App\Http\Controllers\HapusBarangController;
use App\Http\Controllers\PembelianUmumController;
use App\Http\Controllers\PersediaanBarangController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\StokKosongController;
//labarugi
use App\Http\Controllers\LabaRugiController;
//laporanstokopname
use App\Http\Controllers\LPopnameController;


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

Route::get('/TerimaBarang', [TerimaBarangController::class, 'index'])->name('terimabarang.index');


//BarangTerhapus-Barang
Route::get('/BarangTerhapus', [BarangTerhapusController::class, 'index'])->name('barangterhapus');



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

//PindahBarang-Pindah Baki
Route::get('/PindahBaki', [PindahBakiController::class, 'index'])->name('pindahbaki');
Route::post('/PindahBaki/getBarang', [PindahBakiController::class, 'getBarang'])->name('pindahbaki.getBarang');
Route::post('/PindahBaki/store', [PindahBakiController::class, 'store'])->name('pindahbaki.store');

//PindahBarang-Riwayat Pindah Barang
Route::get('/RiwayatPindahBarang', [RiwayatPindahBarangController::class, 'index'])->name('riwayatpindahbarang');

//BeliTransasksi-Beli
Route::get('/TransaksiBeli', [TransaksiBeliController::class, 'index'])->name('transaksibeli');
Route::post('/transaksi-beli', [TransaksiBeliController::class, 'store'])->name('transaksibeli.store');
// Route::get('/TransaksiBeli/barang', [TransaksiBeliController::class, 'getBarang'])->name('transaksibeli.getbarang');

//BeliBatal-Beli
Route::get('/BatalBeli', [BatalBeliController::class, 'index'])->name('batalbeli');
Route::post('/BatalBeli/store', [BatalBeliController::class, 'store'])->name('batalbeli.store');

//BeliRiwayat-Beli
Route::get('/RiwayatBeli', [RiwayatBeliController::class, 'index'])->name('riwayatbeli');

//BeliRiwayat-Batal-Beli
Route::get('/RiwayatBatalBeli', [RiwayatBatalBeliController::class, 'index'])->name('riwayatbatalbeli');

//BeliSelisih-Batal-Beli
Route::get('/SelisihBeli', [SelisihBeliController::class, 'index'])->name('selisihbelibatal');

//JualBatal-jual
Route::get('/BatalJual', [BatalJualController::class, 'index'])->name('bataljual');
Route::post('/BatalJual/store', [BatalJualController::class, 'store'])->name('bataljual.store');
Route::get('/riwayat-bataljual', [BatalJualController::class, 'riwayat'])->name('riwayat.bataljual');
Route::get('/get-barang/{barcode}', [BatalJualController::class, 'getBarangByBarcode']);

//JualRiwayat-Batal-jual
Route::get('/RiweayatBatalJual', [RiwayatBatalJualController::class, 'index'])->name('riwayatbataljual');

//JualTransaksi-jual
Route::get('/TransaksiJual', [TransaksiJualController::class, 'index'])->name('transpenjualan');
Route::post('/transaksi/store', [TransaksiJualController::class, 'store'])->name('transpenjualan.store');
Route::get('/transaksi/{nofaktur}/struk', [TransaksiJualController::class, 'struk'])
    ->name('transpenjualan.struk');
Route::get('/transaksi/{nofaktur}/struk-pdf', [TransaksiJualController::class, 'strukPdf'])
    ->name('transpenjualan.struk.pdf');




//JualSelisih-jual
Route::get('/SelisihJual', [SelisihJualController::class, 'index'])->name('selisihjual');
Route::delete('/selisih-jual/{id}', [SelisihJualController::class, 'destroy'])->name('selisihjual.destroy');

//Riwayat-Penjualan-jual
Route::get('/Riwayat-penjualan', [RiwayatPenjualanController::class, 'index'])->name('riwayatpenjualan');

//stokjual-jual
Route::get('/stokjual', [StokJualController::class, 'index'])->name('stokjual');
Route::post('/stokjual', [StokJualController::class, 'store'])->name('stokjual.store');
Route::put('/stokjual/{nofaktur}', [StokJualController::class, 'update'])->name('stokjual.update');
Route::delete('/stokjual/{nofaktur}', [StokJualController::class, 'destroy'])->name('stokjual.destroy');


//daftarPesanan- pesan
Route::get('/daftarPesanan', [DaftarPesananController::class, 'index'])->name('daftarpesanan');
// Route::get('/pesanan/{id}/edit', [DaftarPesananController::class, 'edit'])->name('pesanan.edit');
Route::put('/pesanan/{id}', [DaftarPesananController::class, 'update'])->name('pesanan.update');
Route::delete('/pesanan/{id}', [DaftarPesananController::class, 'destroy'])->name('pesanan.destroy');

//Service-Transaksi Service
Route::get('/TransaksiService', [TransaksiServiceController::class, 'index'])->name('transaksiservice');
Route::post('/TransaksiService/store', [TransaksiServiceController::class, 'store'])->name('transaksiservice.store');
Route::get('/api/trans-service', [TransaksiServiceController::class, 'data']);
Route::get('/transaksiservice/cetak/{id}', [TransaksiServiceController::class, 'cetak'])->name('transaksiservice.cetak');

//Service-Daftar Service
Route::get('/DaftarService', [DaftarServiceController::class, 'index'])->name('daftarservice');

//LaporanBarang-StokBarang
Route::get('/LaporanStokBarang', [StokBarangController::class, 'index'])->name('stokbarang');

//LaporanBarang-StokKosong
Route::get('/LaporanStokKosong', [StokKosongController::class, 'index'])->name('stokkosong');

//LaporanBarang-PersediaanBarang
Route::get('/LaporanPersediaanBarang', [PersediaanBarangController::class, 'index'])->name('persediaanbarang');

//LaporanBarang-BarangCepatLaku
Route::get('/LaporanBarangCepatLaku', [BarangCepatLakuController::class, 'index'])->name('barangcepatlaku');

//LaporanBarang-BarangLambatLaku
Route::get('/LaporanBarangLambatLaku', [BarangLambatLakuController::class, 'index'])->name('baranglambatlaku');

//LaporanBarang-HapusBarang
Route::get('/LaporanHapusBarang', [HapusBarangController::class, 'index'])->name('hapusbarang');

//LaporanPembelian-PembelianUmum
Route::get('/LaporanPembelianUmum', [PembelianUmumController::class, 'index'])->name('pembelianumum');

//StokOpname- Stok Opname Global
Route::get('/stokopname', [StokOpnameController::class, 'index'])->name('stokopname');
Route::get('/stokopname/barang/{kdbaki}', [StokOpnameController::class, 'getBarangByBaki']);
Route::post('/stokopname/simpan', [StokOpnameController::class, 'simpan']);


//backup_data-utility
Route::get('/backup', [BackupController::class, 'index'])->name('backup');
// Route::post('/backup', [BackupController::class, 'backup'])->name('backup.run');
// Route::post('/restore', [BackupController::class, 'restore'])->name('restore.run');

//UtilitySetting-QR Direktur
Route::get('/setting-qr_direktur', [QRDirekturController::class, 'index'])->name('qr_direktur');
Route::post('/setting-qr_direktur', [QRDirekturController::class, 'store'])->name('qr_direktur.store');

//Laporan Laba Rugi
Route::get('/laba-rugi', [LabaRugiController::class, 'index'])->name('labarugi');
Route::get('/laba-rugi', [LabaRugiController::class, 'labaRugi'])->name('laporan.labarugi');
Route::post('/laba-rugi', [LabaRugiController::class, 'labaRugiShow'])->name('laporan.labarugi.show');

//Laporan Stok Opname
Route::get('/laporan/stok-opname', [LPopnameController::class, 'index'])->name('laporan.stokopname');
Route::post('/laporan/stok-opname', [LPopnameController::class, 'show'])->name('laporan.stokopname.show');

Route::get('/', function () {
    return view('login');
});
