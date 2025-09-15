<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangLambatLakuController extends Controller
{
    public function index(Request $request)
    {
        // Query: ambil barang dengan stok terjual paling sedikit
        $data = DB::table('stokjual')
            ->select('namabarang', DB::raw('SUM(stokterjual) as total_terjual'))
            ->groupBy('namabarang')
            ->orderBy('total_terjual', 'asc') // urut dari terkecil
            ->limit(50)
            ->get();

        return view('laporan.laporanbarang.baranglambatlaku', compact('data'));
    }
}
