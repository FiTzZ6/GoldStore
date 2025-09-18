<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuciSepuh;

class LaporanCuciSepuhController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date', date('Y-m-d'));
        $end = $request->input('end_date', date('Y-m-d'));
        $barang = $request->input('barang', '');

        $query = CuciSepuh::whereBetween('tanggal_cuci', [$start, $end]);

        if ($barang) {
            $query->where('jenis_barang', $barang);
        }

        $cucisepuhs = $query->get();

        return view('laporan.laporancucisepuh', compact('cucisepuhs', 'start', 'end', 'barang'));
    }
}
