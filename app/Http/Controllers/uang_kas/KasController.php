<?php

namespace App\Http\Controllers\uang_kas;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use Illuminate\Http\Request;
use App\Models\ParameterKas; 

class KasController extends Controller
{
    public function index()
    {
        $kas = Kas::orderBy('tanggal', 'desc')->get();
        $parameterKasList = ParameterKas::all();
        return view('uang_kas.uang_kas', compact('kas', 'parameterKasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlahkas' => 'required|numeric',
            'idparameterkas' => 'required|string',
            'type' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $kas = Kas::create($request->all());

        return response()->json(['success' => true, 'data' => $kas]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlahkas' => 'required|numeric',
            'idparameterkas' => 'required|string',
            'type' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $kas = Kas::findOrFail($id);
        $kas->update($request->all());

        return response()->json(['success' => true, 'data' => $kas]);
    }

    public function destroy($id)
    {
        Kas::destroy($id);

        return response()->json(['success' => true]);
    }
}
