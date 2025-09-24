<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormKetidaksesuaian;

class RiwayatKetidaksesuaianController extends Controller
{
    public function index()
    {
        // ambil semua data form ketidaksesuaian + relasi barang & pelanggan
        $forms = FormKetidaksesuaian::with(['barang', 'pelanggan'])->latest()->get();

        return view('barang.rongsok.riwayatketidaksesuai', compact('forms'));
    }
}
