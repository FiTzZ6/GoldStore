<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LPopnameController extends Controller
{
    // Halaman awal (form filter)
    public function index()
    {
        return view('laporan.laporanopname.lpopname', [
            'reports' => [],
            'start'   => null,
            'end'     => null
        ]);
    }

    // Menampilkan hasil laporan
    public function show(Request $request)
    {
        $start = $request->input('start_date');
        $end   = $request->input('end_date');

        // Query stok opname (sesuaikan tabel sesuai DB kamu)
        $reports = DB::table('stok_opname')
            ->select('tanggal', 'kode_barang', 'nama_barang', 'stok_sistem', 'stok_fisik')
            ->when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween('tanggal', [$start, $end]);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('laporan.laporanopname.lpopname', [
            'reports' => $reports,
            'start'   => $start,
            'end'     => $end
        ]);
    }
}
