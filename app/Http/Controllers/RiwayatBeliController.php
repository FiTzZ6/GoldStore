<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\TransBeli;

class RiwayatBeliController extends Controller
{
    public function index(Request $request)
    {
        $query = TransBeli::query();

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $end = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // Search global (nama, faktur, barcode, deskripsi, dll.)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($qbuilder) use ($search) {
                $qbuilder->where('namapenjual', 'like', "%{$search}%")
                    ->orWhere('nofaktur', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $riwayat = $query->orderBy('created_at', 'desc')->get();

        return view('beli.riwayat_beli', compact('riwayat'));
    }

    public function cetakStruk($nofaktur)
    {
        $items = TransBeli::where('nofaktur', $nofaktur)->get();

        if ($items->isEmpty()) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $tanggal = $items->first()->created_at;
        $total = $items->sum('total');
        $pelanggan = $items->first()->namapenjual ?? '-'; // di beli, pakai penjual
        $staff = $items->first()->staff ?? '-';
        $pembayaran = $items->first()->pembayaran ?? '-';

        return view('beli.strukbeli', compact(
            'nofaktur',
            'items',
            'tanggal',
            'total',
            'pelanggan',
            'staff',
            'pembayaran'
        ));
    }


}