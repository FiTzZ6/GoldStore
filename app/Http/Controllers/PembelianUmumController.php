<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransBeli;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianUmumController extends Controller
{
    // Tampilkan laporan di web
    public function index(Request $request)
    {
        $start = $request->start_date ?? date('Y-m-01');
        $end = $request->end_date ?? date('Y-m-d');

        $startDate = date('Y-m-d 00:00:00', strtotime($start));
        $endDate = date('Y-m-d 23:59:59', strtotime($end));

        $pembelian = TransBeli::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laporan.laporanpembelian.pembelianumum', compact('pembelian', 'start', 'end'));
    }

    // Cetak surat laporan resmi (PDF)
    public function cetak(Request $request)
    {
        $ids = $request->id; // array id yang dipilih (optional)

        // default start/end jika tidak dikirim
        $start = $request->start_date ?? null;
        $end = $request->end_date ?? null;

        if ($ids && is_array($ids) && count($ids) > 0) {
            // ambil berdasarkan id yang dipilih
            $pembelian = TransBeli::whereIn('id', $ids)
                ->orderBy('created_at', 'desc')
                ->get();

            // kalau start/end tidak dikirim dari form, kita set berdasarkan data terpilih (min/max)
            if (!$start) {
                $min = $pembelian->min('created_at'); // bisa null
                $start = $min ? date('Y-m-d', strtotime($min)) : date('Y-m-d');
            }
            if (!$end) {
                $max = $pembelian->max('created_at');
                $end = $max ? date('Y-m-d', strtotime($max)) : date('Y-m-d');
            }
        } else {
            // fallback: ambil berdasarkan rentang tanggal
            $start = $start ?? date('Y-m-01');
            $end = $end ?? date('Y-m-d');

            $startDate = date('Y-m-d 00:00:00', strtotime($start));
            $endDate = date('Y-m-d 23:59:59', strtotime($end));

            $pembelian = TransBeli::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // pastikan variabel start & end ter-pass ke view
        $pdf = Pdf::loadView('laporan.laporanpembelian.cetak', compact('pembelian', 'start', 'end'));

        return $pdf->stream('Laporan_Pembelian.pdf');
    }
}
