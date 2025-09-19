<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuciSepuh;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatCuciController extends Controller
{
    public function index(Request $request)
    {
        $query = CuciSepuh::query();

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('tanggal_cuci', [$start, $end]);
        }

        // Urutkan terbaru
        $riwayats = $query->orderBy('tanggal_cuci', 'desc')->get();

        return view('barang.cucisepuh.riwayatcuci', compact('riwayats'));
    }
    public function cetakStruk($id)
    {
        $cuci = CuciSepuh::findOrFail($id);

        $pdf = Pdf::loadView('barang.cucisepuh.struk', compact('cuci'))
            ->setPaper('A5', 'portrait'); // Bisa A4, A5, dsb

        return $pdf->download('struk-cucisepuh-' . $cuci->id_cuci . '.pdf');
    }
}
