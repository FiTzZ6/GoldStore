<?php

namespace App\Http\Controllers;

use App\Models\PindahBaki;
use App\Models\Baki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PindahBakiController extends Controller
{
    public function index()
    {
        // Nomor tampilan awal (opsional). Nomor final tetap dibuat saat simpan.
        $baki = Baki::all();

        $today = now()->format('Ymd');
        $countToday = DB::table('pindah_baki')
            ->whereDate('created_at', now())
            ->count();

        $pb = "PB-{$today}-" . str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

        return view('barang.pindahbarang.pindahbaki', compact('pb', 'baki'));
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

        // --- Generate No Faktur dengan format PB-4RANDOM-4RANDOM ---
        $noPindah = "PB-" . rand(1000, 9999) . "-" . rand(1000, 9999);

        // Cek dulu apakah sudah ada yang sama, kalau ada generate ulang
        while (DB::table('pindah_baki')->where('fakturpindahbaki', $noPindah)->exists()) {
            $noPindah = "PB-" . rand(1000, 9999) . "-" . rand(1000, 9999);
        }

        foreach ($items as $item) {
            DB::table('pindah_baki')->insert([
                'fakturpindahbaki' => $noPindah,
                'barcode' => $item['barcode'],
                'namabarang' => $item['namabarang'] ?? null, // isi kalau mau
                'kdbaki_asal' => $item['kdbaki'],
                'kdbaki_tujuan' => $tujuan,
                'created_at' => now()
            ]);

            // update lokasi baki barang
            DB::table('barang')
                ->where('barcode', $item['barcode'])
                ->update(['kdbaki' => $tujuan]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan',
            'nofaktur' => $noPindah
        ]);
    }

}