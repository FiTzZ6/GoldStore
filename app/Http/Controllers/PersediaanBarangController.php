<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\JenisBarang;
use App\Models\Baki;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PersediaanBarangController extends Controller
{
    public function index(Request $request)
    {
        // ambil nilai filter dari request
        $kategori = $request->get('kategori');
        $jenis = $request->get('jenis');
        $baki = $request->get('baki');

        // query barang dengan relasi
        $barang = Barang::with(['KategoriBarang', 'JenisBarang', 'baki', 'Cabang'])
            ->when($kategori && $kategori !== 'all', function ($query) use ($kategori) {
                $query->where('kdkategori', $kategori);
            })
            ->when($jenis && $jenis !== 'all', function ($query) use ($jenis) {
                $query->where('kdjenis', $jenis);
            })
            ->when($baki && $baki !== 'all', function ($query) use ($baki) {
                $query->where('kdbaki', $baki);
            })
            ->paginate(10);

        // ambil semua data untuk dropdown
        $kategoriList = KategoriBarang::all();
        $jenisList = JenisBarang::all();
        $bakiList = Baki::all();

        $tanggal = Carbon::now()->format('d/m/Y');

        return view('laporan.laporanbarang.persediaanbarang', compact(
            'barang',
            'tanggal',
            'kategoriList',
            'jenisList',
            'bakiList',
            'kategori',
            'jenis',
            'baki'
        ));
    }
}
