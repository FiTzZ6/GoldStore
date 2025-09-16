<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransPenjualan;

class LPPenjualanStaffController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = date('Y-m-d');

        // ambil filter dari request
        $start = $request->start_date ?? null;
        $end = $request->end_date ?? null;
        $staff = $request->staff ?? 'SEMUA STAFF';

        $query = TransPenjualan::query();

        // filter tanggal
        if ($start && $end) {
            $query->whereBetween('created_at', [$start . " 00:00:00", $end . " 23:59:59"]);
        }

        // filter staff
        if ($staff !== 'SEMUA STAFF') {
            $query->where('namastaff', $staff);
        }

        // ambil data transaksi
        if ($staff === 'SEMUA STAFF') {
            // kalau semua staff -> batasi 5 data per halaman
            $data = $query->orderBy('created_at', 'desc')
                ->paginate(5)
                ->withQueryString();
        } else {
            // kalau staff tertentu -> tampilkan semua data (paginate besar biar 1 halaman)
            $data = $query->orderBy('created_at', 'desc')
                ->paginate(100000) // semua data dalam 1 halaman
                ->withQueryString();
        }

        // ambil daftar staff unik untuk filter dropdown
        $listStaff = TransPenjualan::select('namastaff')->distinct()->pluck('namastaff');

        return view('laporan.laporanpenjualan.penjualanstaff', compact(
            'data',
            'tanggal',
            'start',
            'end',
            'listStaff',
            'staff'
        ));
    }
}
