<?php

namespace App\Http\Controllers;

use App\Models\TransPenjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;


class RiwayatPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = TransPenjualan::query();

        // filter tanggal (opsional)
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // filter pencarian
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('namabarang', 'like', "%$search%")
                    ->orWhere('namapelanggan', 'like', "%$search%");
            });
        }

        // group by nofaktur
        $transaksi = $query->orderBy('created_at', 'desc')->get()
            ->groupBy('nofaktur');

        return view('jual.riwayatpenjualan', compact('transaksi'));
    }
}