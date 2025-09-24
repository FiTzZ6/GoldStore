<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormKetidaksesuaian;
use App\Models\Barang;
use App\Models\Pelanggan;

class FormKetidaksesuaianController extends Controller
{
    public function index()
    {
        $forms = FormKetidaksesuaian::with(['barang','pelanggan'])->get();
        $barang = Barang::all();
        $pelanggan = Pelanggan::all();

        return view('barang.rongsok.formketidaksesuai', compact('forms','barang','pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdbarang' => 'required',
            'kdpelanggan' => 'required',
            'deskripsi' => 'required',
        ]);

        FormKetidaksesuaian::create($request->all());

        return redirect()->route('formketidaksesuaian')
                         ->with('success','Formulir berhasil disimpan');
    }
}
