<?php

namespace App\Http\Controllers;

use App\Models\FormPenyimpanan;

class RiwayatPenyimpananController extends Controller
{
    public function index()
    {
        $riwayat = FormPenyimpanan::with(['barang', 'pelanggan'])->latest()->get();
        return view('barang.cucisepuh.riwayatpenyimpanan', compact('riwayat'));
    }
}
