<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use Illuminate\Support\Facades\Storage;

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
            $logoName = time() . '_' . $request->logo->getClientOriginalName();
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

    public function update(Request $request, $kdtoko)
    {
        $toko = Cabang::findOrFail($kdtoko);

        $request->validate([
            'kdtoko' => 'required|max:50|unique:toko,kdtoko',
            'namatoko' => 'nullable|max:100',
            'alamattoko' => 'nullable|max:100',
            'area' => 'nullable|max:50',
            'kdtoko' => 'required|max:50|unique:toko,kdtoko,' . $toko->kdtoko . ',kdtoko',
        ]);

        $logoName = $toko->logo;
        if ($request->hasFile('logo')) {
            if ($logoName && Storage::exists('public/cabang_foto/' . $logoName)) {
                Storage::delete('public/cabang_foto/' . $logoName);
            }
            $logoName = time() . '_' . $request->logo->getClientOriginalName();
            $request->logo->storeAs('public/cabang_foto', $logoName);
        }

        $toko->update([
            'kdtoko' => $request->kdtoko,
            'namatoko' => $request->namatoko,
            'alamattoko' => $request->alamattoko,
            'area' => $request->area,
            'logo' => $logoName
        ]);

        return redirect()->back()->with('success', 'Cabang berhasil diperbarui');
    }

    public function destroy($kdtoko)
    {
        $toko = Cabang::findOrFail($kdtoko);

        if ($toko->logo && Storage::exists('public/cabang_foto/' . $toko->logo)) {
            Storage::delete('public/cabang_foto/' . $toko->logo);
        }

        $toko->delete();

        return redirect()->back()->with('success', 'Cabang berhasil dihapus');
    }
}
