<?php
namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function index()
    {
        $kas = Kas::orderBy('tanggal', 'desc')->get();
        return view('uang_kas.uang_kas', compact('kas'));
    }

    public function create()
    {
        return view('kas.create');
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

        Kas::create($request->all());
        return redirect()->route('uang_kas.uang_kas')->with('success', 'Data kas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kas = Kas::findOrFail($id);
        return view('kas.edit', compact('kas'));
    }

    public function update(Request $request, $id)
    {
        $kas = Kas::findOrFail($id);
        $kas->update($request->all());
        return redirect()->route('uang_kas.uang_kas')->with('success', 'Data kas berhasil diperbarui');
    }

    public function destroy($id)
    {
        Kas::destroy($id);
        return redirect()->route('uang_kas.uang_kas')->with('success', 'Data kas berhasil dihapus');
    }
}
