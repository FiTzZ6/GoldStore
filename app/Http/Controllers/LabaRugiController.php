<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    // GET /laporan/laba-rugi
    public function labaRugi()
    {
        return view('laporan.laporanlabarugi.labarugi');
    }

    // POST /laporan/laba-rugi
    public function labaRugiShow(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $reports = DB::table('penjualan')
            ->selectRaw('DATE(tanggal) as date,
                        SUM(total_jual) as sales,
                        SUM(hpp) as hpp,
                        SUM(total_jual - hpp) as profit')
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('laporan.laporanlabarugi.labarugi', [
            'reports' => $reports,
            'start'   => $start,
            'end'     => $end
        ]);
    }
}
