<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangCepatLakuController extends Controller
{
    public function index()
    {
        // Query: ambil barang dengan stok terjual terbanyak
        $data = DB::table('stokjual')
            ->select('namabarang', DB::raw('SUM(stokterjual) as total_terjual'))
            ->groupBy('namabarang')
            ->orderByDesc('total_terjual')
            ->limit(50)
            ->get();

        return view('laporan.laporanbarang.barangcepatlaku', compact('data'));
    }
}
