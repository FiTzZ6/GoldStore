<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanTukarPoinController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input tanggal dari request
        $start = $request->input('start_date', date('Y-m-01')); // default awal bulan
        $end   = $request->input('end_date', date('Y-m-d'));    // default hari ini

        // Ambil daftar merchandise untuk filter
        $merchList = DB::table('merchandise')->get();

        // Query redeem
        $query = DB::table('redeem')
            ->join('pelanggan', 'redeem.kdpelanggan', '=', 'pelanggan.kdpelanggan')
            ->join('merchandise', 'redeem.merch_id', '=', 'merchandise.id')
            ->select('redeem.*', 'pelanggan.namapelanggan', 'merchandise.nama_merch')
            ->whereBetween('redeem.created_at', [$start . " 00:00:00", $end . " 23:59:59"])
            ->orderBy('redeem.created_at', 'desc');

        // Filter by merch
        $merch = $request->input('merch');
        if (!empty($merch)) {
            $query->where('redeem.merch_id', $merch);
        }

        $redeems = $query->paginate(10);

        return view('laporan.laporanpoin.laporantukarpoin', compact('redeems', 'start', 'end', 'merchList', 'merch'));
    }
}
