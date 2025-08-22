<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelanggan;
use App\Models\Membership;

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

    public function updateMembership(Request $request)
    {
        $request->validate([
            'gr'   => 'required|numeric|min:0.1',
            'poin' => 'required|integer|min:1',
        ]);

        $membership = Membership::first();

        if ($membership) {
            $membership->update($request->only('gr','poin'));
        } else {
            Membership::create($request->only('gr','poin'));
        }

        return redirect()->back()->with('success', 'Pengaturan Membership berhasil disimpan!');
    }

    public function destroy($id)
    {
        Pelanggan::destroy($id);
        return redirect()->route('pelanggan')->with('success', 'Data berhasil dihapus');
    }

}