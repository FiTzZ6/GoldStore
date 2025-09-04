<?php

namespace App\Http\Controllers;

//waawaaw
use App\Models\PindahBaki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PindahBakiController extends Controller
{
    public function index()
    {
        return view('barang.pindahbarang.pindahbaki');
    }

    public function getBarang(Request $request)
    {
        $barcode = $request->barcode;
        $barang = DB::table('barang')
            ->select('barcode', 'namabarang', 'kdbaki', 'kdjenis', 'berat')
            ->where('barcode', $barcode)
            ->first();

        if ($barang) {
            return response()->json(['status' => true, 'data' => $barang]);
        }
        return response()->json(['status' => false, 'message' => 'Barang tidak ditemukan']);
    }

    // simpan transaksi pindah baki
    public function store(Request $request)
    {
        $tujuan = $request->kdbakitujuan;
        $items = $request->items; // array barang dari JS

        // --- Generate No. Pindah Baki otomatis ---
        $date = date('Ymd'); // format 20250904
        $prefix = "PB-$date-";

        // hitung urutan transaksi hari ini
        $last = DB::table('pindah_baki')
            ->whereDate('created_at', now()) // pastikan ada kolom created_at
            ->count() + 1;

        $noPindah = $prefix . str_pad($last, 3, '0', STR_PAD_LEFT);
        // contoh hasil: PB-20250904-001

        foreach ($items as $item) {
            DB::table('pindah_baki')->insert([
                'barcode'   => $item['barcode'],
                'kdbaki_asal' => $item['kdbaki'],
                'kdbaki_tujuan' => $tujuan,
                'created_at' => now()
            ]);

            // update lokasi baki barang
            DB::table('barang')->where('barcode', $item['barcode'])
                ->update(['kdbaki' => $tujuan]);
        }

        return response()->json(['status' => true, 'message' => 'Data berhasil disimpan']);
    }
}