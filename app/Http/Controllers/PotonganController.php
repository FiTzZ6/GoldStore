<?php

namespace App\Http\Controllers;

use App\Models\Potongan;
use App\Models\KategoriBarang;
use App\Models\Cabang;
use Illuminate\Http\Request;

class PotonganController extends Controller
{
    public function index()
    {
        $potongan = Potongan::with(['kategori', 'toko'])->get();
        $kategori = KategoriBarang::all();
        $toko = Cabang::all();

        return view('datamaster.potongan', compact('potongan', 'kategori', 'toko'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kdpotongan' => 'required|unique:potongan,kdpotongan',
            'kdkategori' => 'required',
            'jumlahpotongan' => 'required|numeric',
            'jenispotongan' => 'required|in:PROSENTASE,RUPIAH',
            'kdtoko' => 'required',
        ]);

        Potongan::create($request->all());

        return redirect()->route('potongan')->with('success', 'Data potongan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $potongan = Potongan::findOrFail($id);

        $potongan->update($request->all());

        return redirect()->route('potongan')->with('success', 'Data potongan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $potongan = Potongan::findOrFail($id);
        $potongan->delete();

        return redirect()->route('potongan')->with('success', 'Data potongan berhasil dihapus.');
    }
}
