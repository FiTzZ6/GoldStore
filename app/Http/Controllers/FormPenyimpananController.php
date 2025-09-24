<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormPenyimpanan;
use App\Models\Barang;
use App\Models\Pelanggan;

class FormPenyimpananController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        $pelanggan = Pelanggan::all();
        return view('barang.cucisepuh.formpenyimpanan', compact('barang','pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdbarang'   => 'required',
            'kondisi'    => 'required',
        ]);

        FormPenyimpanan::create($request->all());

        return redirect()->route('formpenyimpanan')
                         ->with('success', 'Data penyimpanan mutu berhasil disimpan.');
    }
}
