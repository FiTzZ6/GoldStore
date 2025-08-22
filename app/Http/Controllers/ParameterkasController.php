<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParameterKas;
use App\Models\Cabang;

class ParameterKasController extends Controller
{
    public function index()
    {
        $parameterkas = ParameterKas::with('cabang')->get(); // pakai cabang
        $cabang = Cabang::all();
        return view('datamaster.parameterkas', compact('parameterkas', 'cabang'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kdparameterkas' => 'required|max:25|unique:parameterkas,kdparameterkas',
            'parameterkas' => 'required|max:75',
            'kdtoko' => 'required|exists:toko,kdtoko',
        ]);

        ParameterKas::create([
            'kdparameterkas' => $request->kdparameterkas,
            'parameterkas' => $request->parameterkas,
            'kdtoko' => $request->kdtoko,
        ]);

        return redirect()->back()->with('success', 'Parameter Kas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pk = ParameterKas::findOrFail($id);

        $request->validate([
            'kdparameterkas' => 'required|max:25|unique:parameterkas,kdparameterkas,' . $id . ',kdparameterkas',
            'parameterkas' => 'required|max:75',
            'kdtoko' => 'required|exists:toko,kdtoko',
        ]);

        // Kalau primary key diubah
        if ($id !== $request->kdparameterkas) {
            // hapus record lama, lalu buat ulang
            $pk->delete();
            ParameterKas::create([
                'kdparameterkas' => $request->kdparameterkas,
                'parameterkas' => $request->parameterkas,
                'kdtoko' => $request->kdtoko,
            ]);
        } else {
            // update biasa
            $pk->update([
                'parameterkas' => $request->parameterkas,
                'kdtoko' => $request->kdtoko,
            ]);
        }

        return redirect()->back()->with('success', 'Parameter Kas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pk = ParameterKas::findOrFail($id);
        $pk->delete();

        return redirect()->back()->with('success', 'Parameter Kas berhasil dihapus');
    }
}
