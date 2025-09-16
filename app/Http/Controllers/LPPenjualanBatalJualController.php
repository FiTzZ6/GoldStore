<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatalJual;

class LPPenjualanBatalJualController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = date('Y-m-d');
        $start = $request->start_date ?? null;
        $end = $request->end_date ?? null;
        $barang = $request->barang ?? 'SEMUA BARANG';

        $query = BatalJual::query();

        if ($start && $end) {
            $query->whereBetween('created_at', [$start . " 00:00:00", $end . " 23:59:59"]);
        }

        if ($barang !== 'SEMUA BARANG') {
            $query->where('namabarang', $barang);
        }

        // paginate 5 data per halaman
        $data = $query->orderBy('created_at', 'desc')->paginate(5)->withQueryString();

        // ambil daftar barang unik untuk filter
        $listBarang = BatalJual::select('namabarang')->distinct()->pluck('namabarang');

        return view('laporan.laporanpenjualan.bataljual', compact(
            'data',
            'tanggal',
            'start',
            'end',
            'listBarang',
            'barang'
        ));
    }
}
