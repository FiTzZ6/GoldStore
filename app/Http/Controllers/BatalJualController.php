<?php

namespace App\Http\Controllers;

use App\Models\staff;
use App\Models\StokJual;
use App\Models\BatalJual;
use Illuminate\Http\Request;


class BatalJualController extends Controller
{
    public function index()
    {
        $stokjual = StokJual::all();
        $staff = Staff::all();
        return view('jual.bataljual', compact('staff', 'stokjual'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namastaff' => 'required',
            'barcode' => 'required',
            'fakturjual' => 'required',
            'namabarang' => 'required',
            'berat' => 'required|numeric',
            'kadar' => 'required|numeric',
            'ongkos' => 'required|numeric',
            'quantity' => 'required|numeric',
            'harga' => 'required|numeric',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
        ]);

        $fakturBatal = "BJ-HLD-" . date("ymd") . "-" . rand(1, 9999);

        \App\Models\BatalJual::create([
            'namastaff' => $request->namastaff,
            'barcode' => $request->barcode,
            'fakturjual' => $request->fakturjual,
            'fakturbataljual' => $fakturBatal,
            'namabarang' => $request->namabarang,
            'berat' => $request->berat,
            'kadar' => $request->kadar,
            'ongkos' => $request->ongkos,
            'quantity' => $request->quantity,
            'harga' => $request->harga,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
        ]);

        $stok = \App\Models\StokJual::where('barcode', $request->barcode)->first();
        if ($stok) {
            $stok->stok += $request->quantity;
            $stok->save();
        }

        return redirect()->route('bataljual')->with('success', 'Data pembatalan berhasil disimpan!');
    }


    public function getBarangByBarcode(Request $request)
    {
        $barcode = $request->barcode;
        $barang = StokJual::where('barcode', $barcode)->first();

        if ($barang) {
            return response()->json($barang);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function riwayat()
    {
        $riwayat = BatalJual::orderBy('id','desc')->paginate(10);
        // $riwayat = BatalJual::all(); // ambil semua data
        return view('jual.riwayatbataljual', compact('riwayat'));
    }
}