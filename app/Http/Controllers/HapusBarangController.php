<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangTerhapus;
use App\Models\Cabang;
use App\Models\Baki; // tambahkan ini

class HapusBarangController extends Controller
{
    public function index()
    {
        // Ambil semua data barang yang terhapus
        $barangTerhapus = BarangTerhapus::all();

        // Ambil semua toko (cabang)
        $tokos = Cabang::orderBy('kdtoko')->get();

        // Ambil semua baki
        $bakis = Baki::orderBy('kdbaki')->get();

        // Kirim ke view
        return view('laporan.laporanbarang.historyhapus', compact('barangTerhapus', 'tokos', 'bakis'));
    }
}
