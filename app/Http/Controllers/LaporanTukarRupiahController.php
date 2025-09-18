<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanTukarRupiahController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date', date('Y-m-d'));
        $end = $request->input('end_date', date('Y-m-d'));
        $mataUang = $request->input('mata_uang'); // filter mata uang

        $transaksiQuery = DB::table('transaksi_tukar')
            ->when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
            })
            ->when($mataUang, function ($query) use ($mataUang) {
                return $query->where('mata_uang', $mataUang);
            })
            ->orderBy('created_at', 'desc');

        // 🔹 logika pagination / semua data
        if (empty($request->start_date) && empty($request->end_date) && empty($mataUang)) {
            // tidak ada filter → tampilkan 7 data per halaman
            $transaksi = $transaksiQuery->paginate(7)->withQueryString();
        } else {
            // ada filter → tampilkan semua, max 10 data
            $transaksi = $transaksiQuery->take(10)->get();
        }

        $rekap = DB::table('transaksi_tukar')
            ->select('mata_uang', DB::raw('SUM(jumlah) as total_jumlah'), DB::raw('SUM(total_rupiah) as total_rupiah'))
            ->when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
            })
            ->when($mataUang, function ($query) use ($mataUang) {
                return $query->where('mata_uang', $mataUang);
            })
            ->groupBy('mata_uang')
            ->get();

        // 🔹 Ambil daftar mata uang unik untuk filter
        $mataUangList = DB::table('transaksi_tukar')
            ->select('mata_uang')
            ->distinct()
            ->orderBy('mata_uang', 'asc')
            ->pluck('mata_uang');

        return view('laporan.laporantukarrupiah', compact(
            'transaksi',
            'rekap',
            'start',
            'end',
            'mataUang',
            'mataUangList'
        ));
    }
}
