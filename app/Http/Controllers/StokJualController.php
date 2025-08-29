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
            'hargabeli' => 'required|numeric',
            'hargajual' => 'required|numeric',
            'ongkos' => 'nullable|numeric',
            'stok' => 'required|integer',
        ]);

        $barang = Barang::where('barcode', $request->barcode)->first();

        StokJual::create([
            'nofaktur' => StokJual::generateNoFaktur(),
            'barcode' => $request->barcode,
            'namabarang' => $barang->namabarang,
            'berat' => $barang->berat,
            'kadar' => $barang->kadar,
            'hargabeli' => $request->hargabeli ?? $barang->hargabeli,
            'hargajual' => $request->hargajual,
            'ongkos' => $request->ongkos ?? 0,
            'stok' => $request->stok,
        ]);

        return redirect()->route('stokjual')->with('success', 'Barang berhasil ditambahkan ke stok jual');
    }

    public function update(Request $request, $nofaktur)
    {
        $stokjual = StokJual::where('nofaktur', $nofaktur)->firstOrFail();

        $stokjual->update($request->all());

        return redirect()->route('stokjual')->with('success', 'Data stok jual berhasil diupdate');
    }

    public function destroy($nofaktur)
    {
        $stokjual = StokJual::where('nofaktur', $nofaktur)->firstOrFail();
        $stokjual->delete();

        return redirect()->route('stokjual')->with('success', 'Data stok jual berhasil dihapus');
    }
}
