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
            'status' => 'required|in:pending,proses,selesai',
            'metode_bayar' => 'required|in:cash,transfer,qris',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto_barang');

        // contoh perhitungan ongkos cuci (misalnya Rp20.000 per gram)
        $ongkos = $request->berat * 20000;
        $data['ongkos_cuci'] = $ongkos;
        $data['total_bayar'] = $ongkos; // bisa ditambah biaya lain kalau ada

        // upload foto dengan nama custom
        if ($request->hasFile('foto_barang')) {
            $ext = $request->file('foto_barang')->getClientOriginalExtension();
            $filename = 'cucisepuh_' . time() . '.' . $ext;
            $request->file('foto_barang')->storeAs('public/cuci_sepuh', $filename);
            $data['foto_barang'] = $filename;
        }

        CuciSepuh::create($data);

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
            'status' => 'required|in:pending,proses,selesai',
            'metode_bayar' => 'required|in:cash,transfer,qris',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $cuci = CuciSepuh::findOrFail($id);
        $updateData = $request->except('foto_barang');

        // hitung ulang ongkos & total
        $ongkos = $request->berat * 20000;
        $updateData['ongkos_cuci'] = $ongkos;
        $updateData['total_bayar'] = $ongkos;

        // upload foto baru jika ada
        if ($request->hasFile('foto_barang')) {
            $ext = $request->file('foto_barang')->getClientOriginalExtension();
            $filename = 'cucisepuh_' . time() . '.' . $ext;
            $request->file('foto_barang')->storeAs('public/cuci_sepuh', $filename);
            $updateData['foto_barang'] = $filename;
        }

        $cuci->update($updateData);

        return redirect()->route('cucisepuhform')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = CuciSepuh::findOrFail($id);

        // hapus juga foto kalau ada
        if ($data->foto_barang && \Storage::exists('public/cuci_sepuh/' . $data->foto_barang)) {
            \Storage::delete('public/cuci_sepuh/' . $data->foto_barang);
        }

        $data->delete();

        return redirect()->route('cucisepuhform')->with('success', 'Data berhasil dihapus!');
    }
    public function show($id)
    {
        $cuci = CuciSepuh::findOrFail($id);
        return view('barang.cucisepuh.detailcuci', compact('cuci'));
    }

}
