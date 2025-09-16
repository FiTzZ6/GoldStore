<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransPenjualan;
use App\Models\Barang;
use Carbon\Carbon;

class LPPenjualanUmumController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());
        $kategori = $request->get('kategori');
        $jenis = $request->get('jenis');
        $faktur = $request->get('faktur');
        $barang = $request->get('barang');

        $query = TransPenjualan::with(['barang.KategoriBarang', 'barang.JenisBarang']);

        // filter tanggal (berdasarkan created_at)
        $query->whereBetween('created_at', [$start, $end]);

        // filter kategori
        if ($kategori && $kategori !== 'SEMUA KATEGORI') {
            $query->whereHas('barang', function ($q) use ($kategori) {
                $q->where('kdkategori', $kategori);
            });
        }

        // filter jenis
        if ($jenis && $jenis !== 'SEMUA JENIS') {
            $query->whereHas('barang', function ($q) use ($jenis) {
                $q->where('kdjenis', $jenis);
            });
        }

        // filter faktur
        if ($faktur && $faktur !== 'SEMUA FAKTUR') {
            $query->where('nofaktur', $faktur);
        }

        // filter barang
        if ($barang && $barang !== 'SEMUA BARANG') {
            $barangQuery = $barang;

            // format "KD001 - Nama Barang"
            if (strpos($barang, ' - ') !== false) {
                $parts = explode(' - ', $barang, 2);
                $barangQuery = trim($parts[0]);
            }

            $query->whereHas('barang', function ($q) use ($barangQuery) {
                $q->where('kdbarang', 'like', "%{$barangQuery}%")
                    ->orWhere('barcode', 'like', "%{$barangQuery}%")
                    ->orWhere('namabarang', 'like', "%{$barangQuery}%");
            });
        }

        // tanggal realtime (hari ini, tanpa sd)
        $tanggal = Carbon::now()->translatedFormat('d F Y');

        // pagination 7 per halaman
        $penjualan = $query->orderBy('created_at', 'desc')->paginate(7)->withQueryString();

        // list barang untuk datalist
        $barangList = Barang::select('kdbarang', 'barcode', 'namabarang')
            ->orderBy('namabarang')
            ->get();

        return view('laporan.laporanpenjualan.penjualanumum', compact(
            'penjualan',
            'start',
            'end',
            'kategori',
            'jenis',
            'faktur',
            'barang',
            'barangList',
            'tanggal'
        ));
    }
}
