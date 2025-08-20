<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;
use App\Models\KategoriBarang;

class JenisBarangController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10
        if ($perPage === 'all') {
            $jenis = JenisBarang::with('kategori')->get(); // tampilkan semua
        } else {
            $jenis = JenisBarang::with('kategori')->paginate($perPage);
        }
        $kategori = KategoriBarang::all();

        return view('datamaster.jenisbarang', compact('jenis', 'kategori', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdjenis' => 'required|unique:jenis,kdjenis',
            'namajenis' => 'required',
            'kdkategori' => 'required|exists:kategori,kdkategori',
        ]);

        JenisBarang::create($request->all());

        return redirect()->route('jenisbarang')->with('success', 'Jenis barang berhasil ditambahkan!');
    }

    public function update(Request $request, $kdjenis)
    {
        $jenis = JenisBarang::findOrFail($kdjenis);

        $jenis->update($request->all());

        return redirect()->route('jenisbarang')->with('success', 'Jenis barang berhasil diperbarui!');
    }

    public function destroy($kdjenis)
    {
        $jenis = JenisBarang::findOrFail($kdjenis);
        $jenis->delete();

        return redirect()->route('jenisbarang')->with('success', 'Jenis barang berhasil dihapus!');
    }
}
