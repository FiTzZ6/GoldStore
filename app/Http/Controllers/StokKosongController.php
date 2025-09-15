<?php

namespace App\Http\Controllers;

use App\Models\StokJual;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StokKosongController extends Controller
{
    public function index()
    {
        // Ambil semua data stokjual yang stok = 0
        $stokKosong = StokJual::where('stok', 0)->get();

        // Ambil tanggal sekarang
        $tanggal = Carbon::now()->format('d/m/Y');

        return view('laporan.laporanbarang.stokkosong', compact('stokKosong', 'tanggal'));
    }
}
