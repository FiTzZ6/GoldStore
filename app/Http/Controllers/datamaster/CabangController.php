<?php

namespace App\Http\Controllers\datamaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cabang;

class CabangController extends Controller
{
    public function index()
    {
        $tokos = Cabang::orderBy('kdtoko')->get();
        return view('datamaster.cabang', compact('tokos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdtoko' => 'required|max:50|unique:toko,kdtoko',
            'namatoko' => 'nullable|max:100',
            'alamattoko' => 'nullable|max:100',
            'area' => 'nullable|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $logoName = null;
        if ($request->hasFile('logo')) {
            $logoName = $request->logo->getClientOriginalName();
            $request->logo->storeAs('public/cabang_foto', $logoName);
        }

        Cabang::create([
            'kdtoko' => $request->kdtoko,
            'namatoko' => $request->namatoko,
            'alamattoko' => $request->alamattoko,
            'area' => $request->area,
            'logo' => $logoName
        ]);

        return redirect()->back()->with('success', 'Cabang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $toko = Cabang::findOrFail($id);

        $request->validate([
            'namatoko' => 'nullable|max:100',
            'alamattoko' => 'nullable|max:100',
            'area' => 'nullable|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $logoName = $toko->logo;
        if ($request->hasFile('logo')) {
            $logoName = $request->logo->getClientOriginalName();
            $request->logo->storeAs('public/cabang_foto', $logoName);
        }

        $toko->update([
            'namatoko' => $request->namatoko,
            'alamattoko' => $request->alamattoko,
            'area' => $request->area,
            'logo' => $logoName
        ]);

        return redirect()->back()->with('success', 'Cabang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $toko = Cabang::findOrFail($id);
        if ($toko->logo && \Storage::exists('public/cabang_foto/' . $toko->logo)) {
            \Storage::delete('public/cabang_foto/' . $toko->logo);
        }
        $toko->delete();

        return redirect()->back()->with('success', 'Cabang berhasil dihapus');
    }


}
