<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\TransBeli;
use App\Models\TransBatalBeli;
use Illuminate\Http\Request;

class BatalBeliController extends Controller
{
    public function index(Request $request)
    {
        $riwayat = null;
        $staff = Staff::all();

        if ($request->filled('nofakturbeli')) {
            $riwayat = TransBeli::where('nofakturbeli', $request->nofakturbeli)->first();
        }

        return view('beli.batal_beli', compact('riwayat', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nofakturbeli' => 'required',
            'namapenjual' => 'required',
            'namabarang' => 'required',
            'berat' => 'required|numeric',
            'hargabeli' => 'required|numeric',
            'kondisibeli' => 'required',
            'kondisibatalbeli' => 'required',
            'hargabatalbeli' => 'required|numeric',
        ]);

        try {
            $nofakturbatal = 'FBB-' . rand(100000, 999999);

            TransBatalBeli::create([
                 'namastaff' => $request->namastaff, // atau dari request
                'nofakturbeli' => $request->nofakturbeli,
                'nofakturbatalbeli' => $nofakturbatal,
                'namapenjual' => $request->namapenjual,
                'kondisibeli' => $request->kondisibeli,
                'kondisibatalbeli' => $request->kondisibatalbeli,
                'namabarang' => $request->namabarang,
                'berat' => $request->berat,
                'hargabeli' => $request->hargabeli,
                'hargabatalbeli' => $request->hargabatalbeli,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('batalbeli')
                ->with('success', 'Transaksi batal beli berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('batalbeli')
                ->with('error', 'Gagal menyimpan transaksi batal beli: ' . $e->getMessage());
        }
    }

}
