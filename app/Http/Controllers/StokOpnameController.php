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
                'kategori.namakategori as kategori',
                'barang.kdbaki'
            )
            ->get();

        return response()->json($data);
    }

    // Simpan hasil stok opname
    public function simpan(Request $request)
    {
        $data = $request->all();

        // Validasi sederhana
        if (!isset($data['items']) || count($data['items']) === 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data barang']);
        }

        foreach ($data['items'] as $item) {
            DB::table('stok_opname_detail')->insert([
                'tanggal'     => now()->toDateString(),   // otomatis isi tanggal hari ini
                'barcode'     => $item['barcode'],
                'namabarang'  => $item['namabarang'],
                'kdkategori'  => $item['kdkategori'],
                'kdbaki'      => $data['kdbaki'],
                'berat'       => $item['berat'],
                'kadar'       => $item['kadar'],
                'staff'       => auth()->user()->name ?? 'system', // isi dari user login
            ]);
        }

        return response()->json(['success' => true]);
    }
}