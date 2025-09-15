<?php

namespace App\Http\Controllers;

use App\Models\StokJual;
use App\Models\Barang;
use Illuminate\Http\Request;

class StokJualController extends Controller
{
    public function index()
    {
        $stokjual = StokJual::with('barang')->get();

        
        $barang = Barang::all();

        return view('jual.stokjual', compact('stokjual', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:barang,barcode',
            'namabarang' => 'required|string',
            'berat' => 'required|numeric',
            'kadar' => 'required|string',
            'hargabeli' => 'required|numeric',
            'hargajual' => 'required|numeric',
            'ongkos' => 'nullable|numeric',
            'stok' => 'required|integer',
        ]);

        // cek apakah stok jual dengan barcode ini sudah ada
        $stokjual = StokJual::where('barcode', $request->barcode)->first();

        if ($stokjual) {
            // update stok lama
            $stokjual->stok += $request->stok;
            $stokjual->stoktotal += $request->stok;
            $stokjual->save();
        } else {
            // buat data baru kalau belum ada
            StokJual::create([
                'nofaktur' => StokJual::generateNoFaktur(),
                'barcode' => $request->barcode,
                'namabarang' => $request->namabarang,
                'berat' => $request->berat,
                'kadar' => $request->kadar,
                'hargabeli' => $request->hargabeli,
                'hargajual' => $request->hargajual,
                'ongkos' => $request->ongkos ?? 0,
                'stok' => $request->stok,
                'stokterjual' => 0,
                'stoktotal' => $request->stok,
            ]);
        }

        return redirect()->route('stokjual')->with('success', 'Barang berhasil ditambahkan ke stok jual');
    }
    public function update(Request $request, $nofaktur)
    {
        $stokjual = StokJual::where('nofaktur', $nofaktur)->firstOrFail();

        $request->validate([
            'barcode' => 'required|exists:barang,barcode',
            'namabarang' => 'required|string',
            'berat' => 'required|numeric',
            'kadar' => 'required|string',
            'hargabeli' => 'required|numeric',
            'hargajual' => 'required|numeric',
            'ongkos' => 'nullable|numeric',
            'stok' => 'required|integer',
            'stokterjual' => 'required|integer',
        ]);

        // Hitung stok total
        $stokTotal = $request->stok + $request->stokterjual;

        $stokjual->update([
            'barcode' => $request->barcode,
            'namabarang' => $request->namabarang,
            'berat' => $request->berat,
            'kadar' => $request->kadar,
            'hargabeli' => $request->hargabeli,
            'hargajual' => $request->hargajual,
            'ongkos' => $request->ongkos,
            'stok' => $request->stok,
            'stokterjual' => $request->stokterjual,
            'stoktotal' => $stokTotal,
        ]);

        return redirect()->route('stokjual')->with('success', 'Data stok jual berhasil diupdate');
    }

    public function destroy($nofaktur)
    {
        $stokjual = StokJual::where('nofaktur', $nofaktur)->firstOrFail();
        $stokjual->delete();

        return redirect()->route('stokjual')->with('success', 'Data stok jual berhasil dihapus');
    }
}
