<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kondisi;

class KondisiBarangController extends Controller
{
    public function index()
    {
        $kondisi = Kondisi::all();
        return view('datamaster.kondisibarang', compact('kondisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kondisibarang' => 'required|string|max:50'
        ]);

        Kondisi::create($request->all());
        return redirect()->route('kondisibarang')->with('success', 'Kondisi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kondisibarang' => 'required|string|max:50'
        ]);

        $kondisi = Kondisi::findOrFail($id);
        $kondisi->update($request->all());
        return redirect()->route('kondisibarang')->with('success', 'Kondisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kondisi = Kondisi::findOrFail($id);
        $kondisi->delete();
        return redirect()->route('kondisibarang')->with('success', 'Kondisi berhasil dihapus!');
    }
}
