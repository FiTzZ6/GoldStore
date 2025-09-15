<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanTransKasController extends Controller
{
    public function index(Request $request)
    {
        // ambil filter type (masuk / keluar)
        $type = $request->input('type');

        // query data kas dengan paginate
        $kas = Kas::with('parameterKas')
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(7) // tampilkan 7 data per halaman
            ->withQueryString(); // biar filter tetap ada saat pindah halaman

        // saldo awal
        $kasAwal = DB::table('kas_awal')->value('jumlahkas');

        return view('laporan.laporankas', compact('kas', 'kasAwal'));
    }
}
