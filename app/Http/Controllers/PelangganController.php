<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // cari pelanggan terakhir
        $lastPelanggan = Pelanggan::orderBy('id', 'desc')->first();

        if ($lastPelanggan) {
            // ambil angka di akhir kdpelanggan (contoh: SAMBAS1210300032 -> 1210300032)
            preg_match('/(\d+)$/', $lastPelanggan->kdpelanggan, $matches);
            $lastNumber = $matches[1] ?? 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1210300001; // angka awal kalau belum ada pelanggan
        }

        // gabungkan prefix SAMBAS + nomor urut
        $newKode = 'SAMBAS' . $newNumber;

        // validasi input lain
        $request->validate([
            'namapelanggan' => 'required',
            'alamatpelanggan' => 'nullable',
            'notelp' => 'nullable',
        ]);

        Pelanggan::create([
            'kdpelanggan' => $newKode,
            'namapelanggan' => $request->namapelanggan,
            'alamatpelanggan' => $request->alamatpelanggan,
            'notelp' => $request->notelp,
            'poin' => 0,
        ]);

        return redirect()->route('pelanggan')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update([
            'namapelanggan' => $request->namapelanggan,
            'alamatpelanggan' => $request->alamatpelanggan,
            'notelp' => $request->notelp,
            'poin' => $request->poin,
        ]);

        return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function updateMembership(Request $request)
    {
        $request->validate([
            'gr' => 'required|numeric|min:0.1',
            'poin' => 'required|integer|min:1',
        ]);

        $membership = Membership::first();

        if ($membership) {
            $membership->update($request->only('gr', 'poin'));
        } else {
            Membership::create($request->only('gr', 'poin'));
        }

        return redirect()->back()->with('success', 'Pengaturan Membership berhasil disimpan!');
    }

    public function destroy($id)
    {
        Pelanggan::destroy($id);
        return redirect()->route('pelanggan')->with('success', 'Data berhasil dihapus');
    }

}