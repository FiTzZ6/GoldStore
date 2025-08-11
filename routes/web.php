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

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'masuk'])->name('login.masuk');
Route::post('/login', [LoginController::class, 'masuk'])->name('login.proses');
Route::post('/register', [LoginController::class, 'daftarakun'])->name('register');
Route::get('/register', [LoginController::class, 'formregister'])->name('register');
Route::post('/register', [LoginController::class, 'daftarakun'])->name('register.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/jual', [JualController::class, 'index'])->name('jual.index');

Route::get('/laporan/dashboard', [LaporanController::class, 'dashboard'])->name('laporan.dashboard');

Route::get('/utility', [UtilityController::class, 'index'])->name('utility.company_profile');
Route::get('/utility/company-profile', [UtilityController::class, 'companyProfile'])
    ->name('utility.company_profile');
Route::post('/utility/company-profile', [UtilityController::class, 'setUpCompany'])
    ->name('utility.set_up_company');

Route::get('/datamaster/area', [DataController::class, 'area'])->name('datamaster.area');

Route::get('/', function () {
    return view('login');
});
