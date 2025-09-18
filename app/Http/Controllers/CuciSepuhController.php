<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuciSepuh;
use App\Models\Barang;

class CuciSepuhController extends Controller
{
    public function index()
    {
        $formulirs = CuciSepuh::all();
        $barangs = Barang::select('namabarang', 'berat', 'kadar')->get();
        return view('barang.cucisepuh.cucisepuhform', compact('formulirs', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'jenis_barang' => 'required|string|max:100',
            'berat' => 'required|numeric',
            'karat' => 'required|numeric',
            'tanggal_cuci' => 'required|date',
            'status' => 'required|in:pending,proses,selesai'
        ]);

        CuciSepuh::create($request->all());

        return redirect()->route('cucisepuhform')->with('success', 'Data berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'jenis_barang' => 'required|string|max:100',
            'berat' => 'required|numeric',
            'karat' => 'required|numeric',
            'tanggal_cuci' => 'required|date',
            'status' => 'required|in:pending,proses,selesai'
        ]);

        $data = CuciSepuh::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('cucisepuhform')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = CuciSepuh::findOrFail($id);
        $data->delete();

        return redirect()->route('cucisepuhform')->with('success', 'Data berhasil dihapus!');
    }
}

