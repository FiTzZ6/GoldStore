<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('datamaster.datapelanggan', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdpelanggan' => 'required|unique:pelanggan',
            'namapelanggan' => 'required',
            'alamatpelanggan' => 'nullable',
            'notelp' => 'nullable',
        ]);

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update([
            'namapelanggan' => $request->namapelanggan,
            'alamatpelanggan' => $request->alamatpelanggan,
            'notelp'        => $request->notelp,
            'poin'          => $request->poin,
        ]);

        return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Pelanggan::destroy($id);
        return redirect()->route('pelanggan')->with('success', 'Data berhasil dihapus');
    }

}