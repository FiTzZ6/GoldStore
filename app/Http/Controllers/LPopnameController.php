<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LPopnameController extends Controller
{
    // Halaman awal (langsung tampil semua data)
    public function index()
    {
        // Ambil semua data tanpa pagination
        $reports = DB::table('stok_opname_detail as s')
            ->leftJoin('baki as b', 's.kdbaki', '=', 'b.kdbaki')
            ->select(
                's.tanggal',
                's.barcode',
                's.namabarang',
                's.namakategori as kategori',
                'b.namabaki as baki',
                's.berat',
                's.kadar',
                's.staff'
            )
            ->orderBy('s.tanggal', 'asc')
            ->get(); // ambil semua data

        return view('laporan.laporanopname.lpopname', [
            'reports' => $reports,
            'start' => null,
            'end' => null,
            'search' => null,
            'isFilter' => false
        ]);
    }

    public function show(Request $request)
    {
        $start = $request->input('start_date');
        $end   = $request->input('end_date');
        $search = $request->input('search');

        $query = DB::table('stok_opname_detail as s')
            ->leftJoin('baki as b', 's.kdbaki', '=', 'b.kdbaki')
            ->select(
                's.tanggal',
                's.barcode',
                's.namabarang',
                's.namakategori as kategori',
                'b.namabaki as baki',
                's.berat',
                's.kadar',
                's.staff'
            )
            ->orderBy('s.tanggal', 'asc');

        // Filter tanggal jika ada
        if ($start && $end) {
            $query->whereBetween('s.tanggal', [$start, $end]);
            $isFilter = true;
        } else {
            $isFilter = false;
        }

        // Filter search jika ada
        if ($search) {
            $query->where('s.namabarang', 'like', "%{$search}%");
            $isFilter = true;
        }

        // Ambil semua data hasil query
        $reports = $query->get();

        return view('laporan.laporanopname.lpopname', [
            'reports' => $reports,
            'start' => $start,
            'end' => $end,
            'search' => $search,
            'isFilter' => $isFilter
        ]);
    }
}
