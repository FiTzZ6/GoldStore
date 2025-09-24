<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransPenjualan;
use App\Models\TransBeli;
use App\Models\Barang;
use Carbon\Carbon;

class LabaRugiController extends Controller
{
    // GET /laporan/laba-rugi
    public function index()
    {
        return view('laporan.laporanlabarugi.labarugi', [
            'reports' => [],
            'start' => null,
            'end' => null
        ]);
    }

    // POST /laporan/laba-rugi
    public function show(Request $request)
    {
        $start = Carbon::parse($request->start_date)->startOfDay(); // 2025-09-24 00:00:00
        $end = Carbon::parse($request->end_date)->endOfDay();     // 2025-09-24 23:59:59

        // 🔹 Total Penjualan (TransPenjualan)
        $penjualan = TransPenjualan::whereBetween('created_at', [$start, $end])
            ->sum('total');

        // 🔹 Total Pembelian (TransBeli)
        $pembelian = TransBeli::whereBetween('created_at', [$start, $end])
            ->sum('total');

        // 🔹 Hitung HPP
        $hpp = TransPenjualan::whereBetween('created_at', [$start, $end])
            ->with('barang')
            ->get()
            ->sum(function ($item) {
                return ($item->barang->hargabeli ?? 0) * ($item->quantity ?? 1);
            });

        // 🔹 Laba Kotor
        $labakotor = $penjualan;

        // 🔹 Laba Bersih
        $lababersih = $labakotor - $hpp;

        $reports = [
            'penjualan' => $penjualan,
            'pembelian' => $pembelian,
            'hpp' => $hpp,
            'labakotor' => $labakotor,
            'lababersih' => $lababersih,
        ];

        return view('laporan.laporanlabarugi.labarugi', [
            'reports' => $reports,
            'start' => $request->start_date,
            'end' => $request->end_date
        ]);
    }
}
