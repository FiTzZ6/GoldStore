<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PrintpdfController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\BeliController;
use App\Http\Controllers\JualController;
use App\Http\Controllers\uang_kas\KasController;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'masuk'])->name('login.masuk');
Route::post('/login', [LoginController::class, 'masuk'])->name('login.proses');
Route::post('/register', [LoginController::class, 'daftarakun'])->name('register');
Route::get('/register', [LoginController::class, 'formregister'])->name('register');
Route::post('/register', [LoginController::class, 'daftarakun'])->name('register.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/jual', [JualController::class, 'index'])->name('jual.index');

Route::get('/laporan/dashboard', [LaporanController::class, 'dashboard'])->name('laporan.dashboard');

Route::get('/utility/company-profile', [UtilityController::class, 'companyProfile'])
    ->name('utility.company.company_profile');
Route::post('/utility/company-profile', [UtilityController::class, 'setUpCompany'])
    ->name('utility.company.set_up_company');
Route::get('/formulir_pp', [UtilityController::class, 'index'])
    ->name('formulir_pp.index');


Route::get('/utility/akun', [LoginController::class, 'listAkun'])->name('utility.akun.table_akun');
Route::get('/utility/akun/create', [LoginController::class, 'createAkun'])->name('user.create');
Route::post('/utility/akun', [LoginController::class, 'storeAkun'])->name('user.store');
Route::get('/utility/akun/{id}/edit', [LoginController::class, 'editAkun'])->name('user.edit');
Route::put('/utility/akun/{id}', [LoginController::class, 'updateAkun'])->name('user.update');
Route::delete('/utility/akun/{id}', [LoginController::class, 'destroyAkun'])->name('user.destroy');
Route::get('/utility/permintaan-pembelian', [UtilityController::class, 'permintaanPembelian'])
    ->name('utility.permintaan_pembelian.index');
Route::get('/formulir-pp/create', [UtilityController::class, 'create'])->name('formulir_pp.create');
Route::post('/utility/permintaan-pembelian/kirim', [UtilityController::class, 'kirimPP'])
    ->name('utility.kirim_pp');



Route::prefix('uang-kas')->name('uang_kas.')->group(function () {
    Route::get('/', [KasController::class, 'index'])->name('index');
    Route::get('/create', [KasController::class, 'create'])->name('create');
    Route::post('/', [KasController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KasController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KasController::class, 'update'])->name('update');
    Route::delete('/{id}', [KasController::class, 'destroy'])->name('destroy');
});



Route::get('/datamaster/area', [DataController::class, 'area'])->name('datamaster.area');

Route::get('/', function () {
    return view('login');
});
