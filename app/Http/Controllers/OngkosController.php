<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ongkos;
use App\Models\Cabang;

class OngkosController extends Controller
{
    public function index()
    {
        $ongkos = Ongkos::with('toko')->get(); // join ke tabel toko
        return view('datamaster.ongkos', compact('ongkos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdtoko' => 'required|exists:toko,kdtoko',
            'ongkos' => 'required|numeric'
        ]);

        Ongkos::create([
            'kdtoko' => $request->kdtoko,
            'ongkos' => $request->ongkos,
        ]);

        return redirect()->back()->with('success', 'Ongkos berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $ongkos = Ongkos::findOrFail($id);

        $request->validate([
            'kdtoko' => 'required|exists:toko,kdtoko',
            'ongkos' => 'required|numeric'
        ]);

        $ongkos->update([
            'kdtoko' => $request->kdtoko,
            'ongkos' => $request->ongkos,
        ]);

        return redirect()->back()->with('success', 'Ongkos berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ongkos = Ongkos::findOrFail($id);
        $ongkos->delete();

        return redirect()->back()->with('success', 'Ongkos berhasil dihapus');
    }
}
