<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransBatalBeli;

class LBatalBeliController extends Controller
{
    // Menampilkan halaman laporan dengan filter
    public function index(Request $request)
    {
        $query = TransBatalBeli::query();

        // Filter berdasarkan no faktur beli
        if ($request->filled('nofakturbeli')) {
            $query->where('nofakturbeli', 'like', '%' . $request->nofakturbeli . '%');
        }

        // Filter berdasarkan tanggal
        $start = $request->start_date ?? now()->format('Y-m-01'); // default awal bulan
        $end = $request->end_date ?? now()->format('Y-m-d');    // default hari ini

        $query->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);

        $transaksi = $query->orderBy('created_at', 'desc')->get();

        return view('laporan.laporanpembelian.batalbeli', compact('transaksi', 'start', 'end'));
    }

    // Cetak laporan yang dipilih
    public function cetak(Request $request)
    {
        $ids = $request->id ?? [];

        if (empty($ids)) {
            return redirect()->route('batalbeli')->with('error', 'Pilih minimal satu transaksi untuk dicetak.');
        }

        $transaksi = TransBatalBeli::whereIn('id', $ids)->orderBy('created_at', 'desc')->get();

        return view('laporan.laporanpembelian.cetak_batalbeli', compact('transaksi'));
    }
}
