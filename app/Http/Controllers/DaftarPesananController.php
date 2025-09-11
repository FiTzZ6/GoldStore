<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class DaftarPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::orderBy('tglpesan', 'desc')->get();
        return view('pesanan.daftarpesanan', compact('pesanan'));
    }

    public function edit($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        return view('pesanan.edit', compact('pesanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tglpesan' => 'required|date',
            'tglambil' => 'nullable|date|after_or_equal:tglpesan',
            'namapemesan' => 'required|string|max:255',
            'alamatpemesan' => 'nullable|string',
            'notelp' => 'nullable|string',
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update($request->all());

        return redirect()->route('daftarpesanan')
            ->with('success', 'Pesanan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return redirect()->route('daftarpesanan')
            ->with('success', 'Pesanan berhasil dihapus');
    }
}
