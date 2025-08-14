<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::orderBy('kdarea')->get();
        return view('datamaster.area', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdarea' => 'required|max:25|unique:area,kdarea',
            'namaarea' => 'nullable|max:100',
        ]);

        Area::create($request->only(['kdarea', 'namaarea']));

        return redirect()->route('area')->with('success', 'Area berhasil ditambahkan');
    }

    public function update(Request $request, $kdarea)
    {
        $request->validate([
            'kdarea' => 'required|max:25',
            'namaarea' => 'nullable|max:100',
        ]);

        $area = Area::findOrFail($kdarea);
        $area->update($request->only(['kdarea', 'namaarea']));

        return redirect()->route('area')->with('success', 'Area berhasil diperbarui');
    }

    public function destroy($kdarea)
    {
        $area = Area::findOrFail($kdarea);
        $area->delete();

        return redirect()->route('area')->with('success', 'Area berhasil dihapus');
    }
}
