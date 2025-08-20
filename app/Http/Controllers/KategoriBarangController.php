<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index(Request $request)
    {
        // default 10 kalau user belum pilih
        $perPage = $request->input('per_page', 10);

        $kategori = KategoriBarang::paginate($perPage);

        return view('datamaster.kategoribarang', compact('kategori', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdkategori' => 'required|unique:kategori',
            'namakategori' => 'required',
            'hargapergr' => 'required|numeric',
            'jumlahkadar' => 'required|numeric',
        ]);

        KategoriBarang::create($request->all());

        return redirect()->route('kategoribarang')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $kdkategori)
    {
        $kategori = KategoriBarang::where('kdkategori', $kdkategori)->firstOrFail();

        $request->validate([
            'kdkategori' => 'required|unique:kategori,kdkategori,' . $kategori->kdkategori . ',kdkategori',
            'namakategori' => 'required',
            'hargapergr' => 'required|numeric',
            'jumlahkadar' => 'required|numeric',
        ]);

        $kategori->update($request->all());

        return redirect()->route('kategoribarang')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy($kdkategori)
    {
        $kategori = KategoriBarang::where('kdkategori', $kdkategori)->firstOrFail();
        $kategori->delete();

        return redirect()->route('kategoribarang')->with('success', 'Kategori berhasil dihapus!');
    }
}
