<?php

namespace App\Http\Controllers\uang_kas;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\ParameterKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index()
    {
        $kas = Kas::with('parameterKas')->orderBy('tanggal', 'desc')->get();
        $parameterKasList = ParameterKas::all();
        $kasAwal = DB::table('kas_awal')->value('jumlahkas');

        return view('uang_kas.uang_kas', compact('kas', 'parameterKasList', 'kasAwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'type' => 'required|string|in:masuk,keluar',
            'jumlahkas' => 'required|numeric',
            'idparameterkas' => 'required|string|exists:parameterkas,kdparameterkas',
        ]);
        $param = ParameterKas::where('kdparameterkas', $request->idparameterkas)->firstOrFail();
        // dd($param);
        $kas = Kas::create([
            'jumlahkas' => $request->jumlahkas,
            'idparameterkas' => $request->idparameterkas,
            'type' => $request->type,
            'tanggal' => $request->tanggal,
            'keterangan' => $param->parameterkas
        ]);

        $kasAwal = DB::table('kas_awal')->value('jumlahkas');
        $kasAwal += $request->type === 'masuk' ? $request->jumlahkas : -$request->jumlahkas;
        DB::table('kas_awal')->update(['jumlahkas' => $kasAwal]);

        return response()->json([
            'success' => true,
            'data' => $kas->load('parameterKas'),
            'kas_awal' => $kasAwal
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlahkas' => 'required|numeric',
            'idparameterkas' => 'required|string|exists:parameterkas,kdparameterkas',
            'type' => 'required|string|in:masuk,keluar',
            'tanggal' => 'required|date',
        ]);

        $kas = Kas::findOrFail($id);
        $kasAwal = DB::table('kas_awal')->value('jumlahkas');

        $kasAwal += $kas->type === 'masuk' ? -$kas->jumlahkas : $kas->jumlahkas;

        $param = ParameterKas::where('kdparameterkas', $request->idparameterkas)->first();

        $kas->update([
            'jumlahkas' => $request->jumlahkas,
            'idparameterkas' => $request->idparameterkas,
            'type' => $request->type,
            'tanggal' => $request->tanggal,
            'keterangan' => $param->parameterkas
        ]);

        $kasAwal += $request->type === 'masuk' ? $request->jumlahkas : -$request->jumlahkas;
        DB::table('kas_awal')->update(['jumlahkas' => $kasAwal]);

        return response()->json([
            'success' => true,
            'data' => $kas->load('parameterKas'),
            'kas_awal' => $kasAwal
        ]);
    }

    public function destroy($id)
    {
        $kas = Kas::findOrFail($id);
        $kasAwal = DB::table('kas_awal')->value('jumlahkas');

        $kasAwal += $kas->type === 'masuk' ? -$kas->jumlahkas : $kas->jumlahkas;
        $kas->delete();

        DB::table('kas_awal')->update(['jumlahkas' => $kasAwal]);

        return response()->json(['success' => true, 'kas_awal' => $kasAwal]);
    }
}
