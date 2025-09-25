<?php

namespace App\Http\Controllers;

use App\Models\StokJual;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = StokJual::with('barang');

        // Filter Toko
        if ($request->filled('toko') && $request->toko != 'SEMUA TOKO') {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('kdtoko', $request->toko);
            });
        }

        // Filter Jenis
        if ($request->filled('jenis') && $request->jenis != 'SEMUA JENIS') {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('kdjenis', $request->jenis);
            });
        }

        // Filter Baki
        if ($request->filled('baki') && $request->baki != 'SEMUA Baki') {
            $query->where('kdbaki', $request->baki);
        }

        $stokjual = $query->get();

        // Ambil data filter
        $toko = \App\Models\Cabang::all();
        $jenis = \App\Models\JenisBarang::all();
        $baki = \App\Models\Baki::all();

        return view('laporan.laporanbarang.stokbarang', compact('stokjual', 'toko', 'jenis', 'baki'));
    }


}
