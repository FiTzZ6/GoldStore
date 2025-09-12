<?php

namespace App\Http\Controllers;

use App\Models\StokJual;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    public function index()
    {
        // ambil semua stokjual + relasi barang
        $stokjual = StokJual::with('barang')->get();

        return view('laporan.laporanbarang.stokbarang', compact('stokjual'));
    }
}
