<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Baki;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class StokOpnameController extends Controller
{
    public function index()
    {
        $baki = Baki::all();
        return view('stokopname.stokopname', compact('baki'));
    }

    // Ambil barang per baki
    public function getBarangByBaki($kdbaki)
    {
        $data = DB::table('barang')
            ->join('kategori', 'barang.kdkategori', '=', 'kategori.kdkategori')
            ->where('barang.kdbaki', $kdbaki)
            ->select(
                'barang.barcode',
                'barang.namabarang',
                'barang.berat',
                'barang.kadar',
                'barang.kdkategori',            // kode kategori
                'kategori.namakategori as kategori', // nama kategori, untuk ditampilkan
                'barang.kdbaki'
            )
            ->get();

        return response()->json($data);
    }

    // Simpan hasil stok opname
    public function simpan(Request $request)
    {
        $data = $request->all();

        if (!isset($data['items']) || count($data['items']) === 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data barang']);
        }

        foreach ($data['items'] as $item) {
            // Simpan kode kategori di kolom namakategori
            DB::table('stok_opname_detail')->insert([
                'tanggal' => now()->toDateString(),
                'barcode' => $item['barcode'],
                'namabarang' => $item['namabarang'],
                'namakategori' => $item['kdkategori'], // simpan kode kategori di kolom namakategori
                'kdbaki' => $data['kdbaki'],
                'berat' => $item['berat'],
                'kadar' => $item['kadar'],
                'staff' => auth()->user()->name ?? 'system',
            ]);
        }

        return response()->json(['success' => true]);
    }
}
